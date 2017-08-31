<?php

namespace App\Managers\Behaviors;

use App\Managers\Powers\UserPowerManager;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Message;
use App\Models\Movie;
use App\User;
use Illuminate\Http\Request;

/**
 * Class JoinMovieBehaviorManager
 * @package App\Managers\Behaviors
 */
class JoinMovieBehaviorManager
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Movie
     */
    private $movie;
    /**
     * @var Group
     */
    private $group;
    /**
     * @var GroupUser
     */
    private $groupUser;

    /**
     * @return $this
     */
    public function joinGroup()
    {
        $this->groupUser = GroupUser::create([
            'FPUBLICTEL'     => 20,
            'FGROUPUSERROLE' => 20,
            'FNEWDATE'       => date('Y-m-d H:i:s'),
            'FEDITDATE'      => date('Y-m-d H:i:s'),
            'FMOVIE'         => $this->movie->FID,
            'FGROUP'         => $this->group->FID,
            'FUSER'          => $this->user->FID,
            'FID'            => GroupUser::max("FID") + 1,
            'FREMARK'        => $this->request->input('job'),
            'FOPEN'          => $this->request->is_join_movie_contacts == 1 ? GroupUser::PHONE_PUBLIC : GroupUser::PHONE_PRIVATE,
            'FOPENED'        => $this->request->is_use_login_phone == 1 ? GroupUser::PHONE_OPENED : GroupUser::PHONE_NOT_OPENED,
        ]);
        return $this;
    }

    /**
     * @return $this
     */
    public function createSharePhones()
    {
        $this->user->createSharePhones($this->groupUser, $this->request->input('is_use_login_phone'));

        return $this;
    }

    /**
     * @return $this
     */
    public function joinHxGroup()
    {
        \Log::info('try to join hx group');
        try {
            if ($this->request->input('is_join_chat_group') == 1) {
                $groups = $this->user->groupsInMovie($this->movie->FID);
                foreach ($groups as $group) {
                    $this->user->joinHxGroup($group);
                }
            }
        } catch (\Exception $exception) {
            return $this;
        } catch (\Error $error) {
            return $this;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function addToMessageReceiver()
    {
        $this->movie->addUserToMessageReceiver($this->user->FID,
            [Message::TYPE_JUZU, Message::TYPE_BLOG]
        );
        $this->movie->addUserToDailyNoticeMessageReceiver($this->user->FID);
        return $this;
    }

    /**
     * @return $this
     */
    public function addToGroupTodos()
    {
        $this->group->addUserToShareTodos($this->user->FID);
        return $this;
    }

    /**
     *
     */
    public function run()
    {

    }

    /**
     * @return $this
     */
    public function assignPower()
    {
        UserPowerManager::assignPowerByGroup($this->user, $this->group);
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function passRequest(Request $request)
    {
        $this->request = $request;
        $this->movie   = Movie::find($this->request->input('movie_id'));
        $this->group   = Group::find($this->request->input('group_id'));
        $this->user    = User::find($this->request->input('user_id'));
        return $this;
    }

    /**
     * Validate the join movie group's conditions.
     * @return $this
     * @throws \Exception
     */
    public function validate()
    {
        if (!$this->user) {
            throw new \Exception('用户不存在');
        }

        if (!$this->movie || $this->movie->closed()) {
            throw new \Exception("剧组已经关闭不允许加入");
        }

        if ($this->movie->FPASSWORD != $this->request->input('password')) {
            throw new \Exception("进入失败,密码错误");
        }

        if ($this->user->isInMovie($this->movie->FID)) {
            throw new \Exception("你已经加入了这个剧组");
        }

        if (!$this->group) {
            throw new \Exception("进入失败,加入的部门不存在");
        }

        if ($this->user->isInGroup($this->group->FID)) {
            throw new \Exception("你已经加入了这个部门");
        }

        return $this;
    }

    /**
     * Add the user into zhipian group if he insists,
     * and only can he require this when he had joined the tongchou group.
     * @return $this
     */
    public function addToZhiPianGroupIf()
    {
        $canJoinZhipianGroup = ($this->user->isTongChouInMovie($this->movie->FID) || $this->user->isChangJiInMovie($this->movie->FID)) &&
                               ($this->request->input('want_join_zhipian') == 1);

        if (!$canJoinZhipianGroup) {
            return $this;
        }
        $this->user->joinGroup($this->movie->getCertainTypeGroup());
        return $this;
    }
}
