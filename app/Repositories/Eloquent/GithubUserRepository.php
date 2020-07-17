<?php
/*
 * @Author: Li Jian
 * @Date: 2020-07-16 14:06:41
 * @LastEditTime: 2020-07-17 10:08:54
 * @LastEditors: Li Jian
 * @Description:
 * @FilePath: /water-environment-end/app/Repositories/Eloquent/GithubUserRepository.php
 * @Motto: MMMMMMMM
 */

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\GithubUserRepositoryInterface;
use App\Models\GithubUser;

class GithubUserRepository extends BaseRepository implements
GithubUserRepositoryInterface
{
    public function getGithub($githubId)
    {
        return GithubUser::where('github_id', $githubId)->first();
    }

    public function create($create)
    {
        GithubUser::insert($create);
        // return $create['github_id'];
        // return GithubUser::where('github_id', $create['github_id'])->first();
    }
}
