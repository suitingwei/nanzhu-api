<?php
namespace App\Repositories;

use App\Formatters\Malls\PurchaseFormatter;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Purchase;
use App\Models\PurchaseAddress;
use App\Models\PurchaseItem;
use App\Models\UserAddress;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Pingpp\Charge;
use Pingpp\Pingpp;

/**
 * Class PurchaseRepository
 * @package App\Repositories
 */
class PurchaseRepository extends Repository
{
    /**
     * This property can only be used to retrive purchase id, because the other attributes will be updated when
     * purchase items created. And the property purchase will be the old version,all these new attributes can not
     * be fetched from this property.
     * @var Purchase
     */
    private $purchase;

    /**
     * @var Charge
     */
    private $charge;

    /**
     * The query parameter for purchase list.
     * @var string
     */
    private $searchScope;

    /**
     * The purchase payment channel.
     * @var string
     */
    private $channel;

    /**
     * The customer user.
     * @var User
     */
    private $user;

    /**
     * PurchaseRepository constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->searchScope = Str::camel($this->request->input('status', 'all'));

        $this->user = User::find($this->request->input('user_id'));

        $this->channel = $this->request->input('channel');
    }

    /**
     * Create the purchase, purchase items,and purchase charge.
     * @return array
     * @throws Exception
     */
    public function create()
    {
        $this->createPurchase();
        $this->createPurchaseItems();
        $this->createPurchaseAddress();
        $this->createPingPPCharge();
        return [$this->charge, $this->purchase];
    }

    /**
     * Create the purchase.
     */
    private function createPurchase()
    {
        \Log::info($this->request->input('note').'++++'.$this->request->input('user_id'));
        return $this->purchase = Purchase::create([
            'user_id' => $this->request->input('user_id'),
            'channel' => $this->request->input('channel'),
            'note'    => $this->request->input('note')
        ]);
    }

    /**
     * Create the purchase address.
     */
    public function createPurchaseAddress()
    {
        $userAddress = UserAddress::find($this->request->user_address_id);

        if (!$userAddress) {
            throw new Exception('用户地址不能为空');
        }

        PurchaseAddress::create([
            'purchase_id'    => $this->purchase->id,
            'receiver_name'  => $userAddress->receiver_name,
            'receiver_phone' => $userAddress->receiver_phone,
            'user_id'        => $this->request->input('user_id'),
            'province'       => $userAddress->province,
            'city'           => $userAddress->city,
            'area'           => $userAddress->area,
            'detail'         => $userAddress->detail,
        ]);
    }

    /**
     * @throws Exception
     */
    public function createPurchaseItems()
    {
        $product = Product::find($this->request->input('product_id'));
        if (!$product) {
            throw new Exception('商品不存在');
        }

        $size = ProductSize::find($this->request->input('product_size_id'));
        if (!$size) {
            throw new Exception('商品尺码不存在');
        }

        $price = $product->prices();
        if (!$price) {
            throw new Exception('商品还没有绑定价格');
        }

        $price = $price->where('product_size_id', $size->id)->first(['price']);

        PurchaseItem::create([
            'user_id'     => $this->request->input('user_id'),
            'purchase_id' => $this->purchase->id,
            'product_id'  => $product->id,
            'title'       => $product->title,
            'size'        => $size->desc,
            'price'       => $price->price,
            'count'       => $this->request->input('count'),
            'cover_url'   => $product->product_cover
        ]);
    }

    /**
     * Create the ping++ charge object for the payment.
     * * @param Purchase $purchase
     *
     * @return Charge
     */
    public function createPingPPCharge(Purchase $purchase = null)
    {
        Pingpp::setApiKey(env('PINGPP_SECRET_KEY'));

        //We'll have to fetch the latest purchase because the purchase total_items_price, and
        //total_items_count will be updated when purchase items created, so the private purchase property
        //will be the old version. And all these attributes will be zero.
        $purchase = $purchase ?: Purchase::find($this->purchase->id);

        return $this->charge = Payment::createPurchaseCharge([
            'purchase_id' => $purchase->id,
            'user_id'     => $purchase->user_id,
            'order_no'    => time() . $purchase->serial_number,
            'amount'      => $purchase->totalPriceInCent(),
            'body'        => $purchase->products()->implode('title', ','),
            'extra'       => [],
            'currency'    => 'cny',
            'subject'     => '南竹商城',
            'client_ip'   => '127.0.0.1',
            'app'         => ['id' => 'app_Sy1eLCKKiL84aLW5'],
            'channel'     => $this->request->input("channel"),
        ]);
    }

    /**
     * Validate the user is the purchase owner.
     *
     * @param $purchaseId
     *
     * @return array
     * @throws Exception
     */
    public function validatePurchaseOwner($purchaseId)
    {
        $purchase = $purchaseId ? Purchase::find($purchaseId) : $this->purchase;

        if (!$purchase->isBelongsTo($this->user->FID)) {
            throw new Exception('无权操作订单');
        }

        return [$this->user, $purchase];
    }

    /**
     * Fetch the purchases for op.
     * @return Collection
     */
    public function fetchListForOp()
    {
        $searchScope = $this->filterQueryAttack();

        return Purchase::orderBy('created_at', 'desc')->$searchScope()->paginate(80)->map(
            PurchaseFormatter::getListFormatterForOp()
        );
    }

    /**
     * Fetch the purchase list for app.
     * Exclude the deleted purchases.
     * @return Collection
     */
    public function fetchListForApp()
    {
        $searchScope = $this->filterQueryAttack();

        return $this->user->purchases()->notDeleted()->$searchScope()->orderBy('created_at', 'desc')->paginate(20)->map(
            PurchaseFormatter::getListFormatter()
        );
    }

    /**
     * Due to the laravel dynamic call.
     * When use $purchases()->searchScope()->paginate(15)...
     * Whene the searchScope equals 'delete', it will fire the
     * $purchase->delete() first............
     * And all datas gone.
     * @return string
     */
    private function filterQueryAttack()
    {
        if ($this->searchScope == 'delete') {
            return 'deleted';
        }
        if ($this->searchScope == 'cancel') {
            return 'canceled';
        }

        return $this->searchScope;
    }

    /**
     * @param $purchaseId
     *
     * @return mixed
     * @throws  Exception
     */
    public function getDetail($purchaseId)
    {
        list($user, $purchase) = $this->validatePurchaseOwner($purchaseId);

        if ($this->fromOp()) {
            $formatter = PurchaseFormatter::getDeatailFormatterForOp();
        } else {
            $formatter = PurchaseFormatter::getDetailFormatter();
        }

        return $formatter($purchase);
    }

}
