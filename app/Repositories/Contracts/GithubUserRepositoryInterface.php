<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-16 14:03:44
 * @LastEditTime: 2020-07-17 09:43:01
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Repositories/Contracts/GithubUserRepositoryInterface.php
 * @Motto: MMMMMMMM
 */

namespace App\Repositories\Contracts;

interface GithubUserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * 通过githubid获取github_user表
     * @param {type}
     * @return:
     */
    public function getGithub($githubId);

    /**
     * 新建用户
     * @param {type}
     * @return mixed
     */
    public function create($create);
}
