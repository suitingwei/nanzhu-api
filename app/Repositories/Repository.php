<?php
namespace App\Repositories;

use Illuminate\Http\Request;

abstract class Repository
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function fetchPaginated()
    {
        if ($this->fromOp()) {
            return $this->fetchListForOp();
        } else {
            return $this->fetchListForApp();
        }
    }

    /**
     * Determine whether the request from.
     * @return boolean
     */
    public function fromApp()
    {
        return $this->request->from == 'app';
    }

    /**
     * Determine whether  the request is from the op.
     * @return boolean
     */
    public function fromOp()
    {
        return $this->request->from == 'op';
    }

    abstract public function fetchListForApp();

    abstract public function fetchListForOp();
}
