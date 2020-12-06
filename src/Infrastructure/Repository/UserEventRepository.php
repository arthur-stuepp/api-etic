<?php


namespace App\Infrastructure\Repository;


use App\Domain\UserEvent\IUserEventRepository;
use App\Domain\UserEvent\UserEvent;
use PDO;

class UserEventRepository extends MysqlRepository implements IUserEventRepository
{
    public function __construct()
    {
        parent::__construct();
        $this->class = UserEvent::class;
        $this->table = 'usersevents';
    }

    public function add(UserEvent $userEvent)
    {
        parent::insert($userEvent->jsonSerialize());

        return $userEvent->user . $userEvent->event;
    }

    public function remove(UserEvent $userEvent)
    {
        $sql = 'DELETE FROM usersevents WHERE user =:user and event=:event';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user', $userEvent->user, PDO::PARAM_INT);
        $stmt->bindValue(':event', $userEvent->event, PDO::PARAM_INT);

        $stmt->execute();

        return (bool)$stmt->rowCount();


    }

    public function list()
    {
        // TODO: Implement list() method.
    }


    public function countEventusers(int $event)
    {
        $sql = 'SELECT COUNT(usersevents.user)FROM `usersevents` WHERE event=:event';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':event', $event, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchColumn();

    }

    public function getUserEvent(UserEvent $userEvent)
    {
        $sql = 'SELECT * FROM usersevents WHERE user =:user and event=:event';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user', $userEvent->user, PDO::PARAM_INT);
        $stmt->bindValue(':event', $userEvent->event, PDO::PARAM_INT);

        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($data) {
            return new $this->class($data);
        } else {
            return false;
        }
    }
}