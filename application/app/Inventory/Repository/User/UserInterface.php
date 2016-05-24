<?php namespace Inventory\Repository\User;

interface UserInterface
{
    public function all(array $params);

    public function allReport();

    public function usersList();

    public function createUser($user);

    public function updateUser($id, $user);

    public function getById($id);

    public function deleteUser($id);

    public function restoreUser($id);

    public function allDeleted();

    public function userCount();

    public function deletedCount();

    public function getCompanyMembers();

    public function getUsersForLpoGenerate();
}
