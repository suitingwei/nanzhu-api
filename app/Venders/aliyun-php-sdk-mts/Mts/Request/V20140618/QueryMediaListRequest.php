<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
namespace Mts\Request\V20140618;

class QueryMediaListRequest extends \RpcAcsRequest
{
    function __construct()
    {
        parent::__construct("Mts", "2014-06-18", "QueryMediaList");
    }

    private $ownerId;

    private $resourceOwnerAccount;

    private $resourceOwnerId;

    private $mediaIds;

    private $includePlayList;

    private $includeSnapshotList;

    private $includeMediaInfo;

    private $ownerAccount;

    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function setOwnerId($ownerId)
    {
        $this->ownerId                    = $ownerId;
        $this->queryParameters["OwnerId"] = $ownerId;
    }

    public function getResourceOwnerAccount()
    {
        return $this->resourceOwnerAccount;
    }

    public function setResourceOwnerAccount($resourceOwnerAccount)
    {
        $this->resourceOwnerAccount                    = $resourceOwnerAccount;
        $this->queryParameters["ResourceOwnerAccount"] = $resourceOwnerAccount;
    }

    public function getResourceOwnerId()
    {
        return $this->resourceOwnerId;
    }

    public function setResourceOwnerId($resourceOwnerId)
    {
        $this->resourceOwnerId                    = $resourceOwnerId;
        $this->queryParameters["ResourceOwnerId"] = $resourceOwnerId;
    }

    public function getMediaIds()
    {
        return $this->mediaIds;
    }

    public function setMediaIds($mediaIds)
    {
        $this->mediaIds                    = $mediaIds;
        $this->queryParameters["MediaIds"] = $mediaIds;
    }

    public function getIncludePlayList()
    {
        return $this->includePlayList;
    }

    public function setIncludePlayList($includePlayList)
    {
        $this->includePlayList                    = $includePlayList;
        $this->queryParameters["IncludePlayList"] = $includePlayList;
    }

    public function getIncludeSnapshotList()
    {
        return $this->includeSnapshotList;
    }

    public function setIncludeSnapshotList($includeSnapshotList)
    {
        $this->includeSnapshotList                    = $includeSnapshotList;
        $this->queryParameters["IncludeSnapshotList"] = $includeSnapshotList;
    }

    public function getIncludeMediaInfo()
    {
        return $this->includeMediaInfo;
    }

    public function setIncludeMediaInfo($includeMediaInfo)
    {
        $this->includeMediaInfo                    = $includeMediaInfo;
        $this->queryParameters["IncludeMediaInfo"] = $includeMediaInfo;
    }

    public function getOwnerAccount()
    {
        return $this->ownerAccount;
    }

    public function setOwnerAccount($ownerAccount)
    {
        $this->ownerAccount                    = $ownerAccount;
        $this->queryParameters["OwnerAccount"] = $ownerAccount;
    }

}