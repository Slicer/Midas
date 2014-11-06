<?php
/*=========================================================================
 MIDAS Server
 Copyright (c) Kitware SAS. 26 rue Louis Guérin. 69100 Villeurbanne, FRANCE
 All rights reserved.
 More information http://www.kitware.com

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

         http://www.apache.org/licenses/LICENSE-2.0.txt

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
=========================================================================*/

/**
 * Feed user policy DAO.
 *
 * @method int getFeedId()
 * @method void setFeedId(int $feedId)
 * @method int getUserId()
 * @method void setUserId(int $userId)
 * @method int getPolicy()
 * @method void setPolicy(int $policy)
 * @method string getDate()
 * @method void setDate(string $date)
 * @method FeedDao getFeed()
 * @method void setFeed(FeedDao $feed)
 * @method UserDao getUser()
 * @method void setUser(UserDao $user)
 * @package Core\DAO
 */
class FeedpolicyuserDao extends AppDao
{
    /** @var string */
    public $_model = 'Feedpolicyuser';
}
