<?php
/**
 * Class Task Model
 *
 * @author Ceytec <david@nani-games.net>
 */
class Task {

    /**
     * Reference Attribute to Database Library
     *
     * @var Database
     */
    private $db;

    /**
     * Constant task value for task classification
     *
     * Use this for changed username confirmation
     */
    const TASK_CONFIRM_PASSWORD = "CONFIRM_PASSWORD";

    /**
     * Constant task value for task classification
     *
     * Use this for changed username confirmation
     */
    const TASK_CONFIRM_USERNAME = "CONFIRM_USERNAME";

    /**
     * Constant task value for task classification
     *
     * Use this for changed email confirmation and
     * additional identity verify
     */
    const TASK_CONFIRM_EMAIL = "CONFIRM_EMAIL";

    /**
     * Constant task value for task classification
     *
     * Use this for new registered users to
     * verify their identity
     */
    const TASK_CONFIRM_IDENTITY = "CONFIRM_IDENTITY";

    /**
     * Constant task value for task classification
     *
     * Use this for changed password confirmation
     */
    const TASK_RESET_PASSWORD = "RESET_PASSWORD";

    /**
     * Task constructor.
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Get type of task
     *
     * @param $task_token
     * @return bool|object
     */
    public function getTaskType($task_token) {
        $this->db->query('SELECT task_type FROM tasks WHERE task_token = :token');
        $this->db->bind(':token', $task_token);
        $row = $this->db->single();
        if($this->db->rowCount() > 0) {
            return $row->task_type;
        } else {
            return false;
        }
    }

    /**
     * Find a task by userId and taskType
     *
     * @param $user_id
     * @param $task_type
     * @return bool|object
     */
    public function findTask($user_id, $task_type) {
            $this->db->query('SELECT * FROM tasks WHERE task_requested_by = :user AND task_type = :type');
            $this->db->bind(':user', $user_id);
            $this->db->bind(':type', $task_type);
            $this->db->execute();
            if($this->db->rowCount() > 0) {
                return $this->db->single();
            } else {
                return false;
            }
        }

    /**
     * Find a task by token
     *
     * @param $token
     * @return bool|object
     */
    public function findTaskByToken($token) {
        $this->db->query('SELECT * FROM tasks WHERE task_token = :token');
        $this->db->bind(':token', $token);
        $this->db->execute();
        if($this->db->rowCount() > 0) {
            return $this->db->single();
        } else {
            return false;
        }
    }

    /**
     * Find associative tasks in mapped
     * tables by refId
     *
     * @param $ref_id
     * @param $task_type
     * @return bool|object
     */
    public function findLinkedTask($ref_id, $task_type) {
        switch($task_type) {
            case $this::TASK_RESET_PASSWORD:
                $this->db->query('SELECT * FROM tasks_reset WHERE task_id = :refId');
                break;
            case $this::TASK_CONFIRM_PASSWORD:
                $this->db->query('SELECT * FROM tasks_pw WHERE task_id = :refId');
                break;
            case $this::TASK_CONFIRM_USERNAME:
                $this->db->query('SELECT * FROM tasks_name WHERE task_id = :refId');
                break;
            case $this::TASK_CONFIRM_EMAIL:
                $this->db->query('SELECT * FROM tasks_mail WHERE task_id = :refId');
                break;
            case $this::TASK_CONFIRM_IDENTITY:
                $this->db->query('SELECT * FROM tasks_identity WHERE task_id = :refId');
                break;
            default:
                return false;
        }
        $this->db->bind(':refId', $ref_id);
        $this->db->execute();
        if($this->db->rowCount() > 0) {
            return $this->db->single();
        } else {
            return false;
        }
    }

    /**
     * Execute tasks
     *
     * @param $task_type
     * @param $requester
     * @param $linked_task
     * @return bool
     */
    public function executeTask($task_type, $requester, $linked_task) {
        switch($task_type) {
            case $this::TASK_CONFIRM_USERNAME:
                $new_username = $linked_task->task_new_name;
                if(!is_null($new_username)) {
                    $this->db->query('UPDATE users SET user_name = :new WHERE user_id = :requester');
                    $this->db->bind(':new', $new_username);
                    $this->db->bind(':requester', $requester);
                    if($this->db->execute()) {
                        if(isLoggedIn()) {
                            $_SESSION['user_name'] = $new_username;
                        }
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
                break;
            case $this::TASK_CONFIRM_PASSWORD:
                    $new_pass_hash = $linked_task->task_new_hash;
                    if(!is_null($new_pass_hash)) {
                        $this->db->query('UPDATE users SET pass_hash = :new WHERE user_id = :requester');
                        $this->db->bind(':new', $new_pass_hash);
                        $this->db->bind(':requester', $requester);
                        if($this->db->execute()) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                    break;
            case $this::TASK_CONFIRM_EMAIL:
                    $new_email = $linked_task->task_new_mail;
                    if(!is_null($new_email)) {
                        $this->db->query('UPDATE users SET user_email = :new WHERE user_id = :requester');
                        $this->db->bind(':new', $new_email);
                        $this->db->bind(':requester', $requester);
                        if($this->db->execute()) {
                            if(isLoggedIn()) {
                                $_SESSION['user_email'] = $new_email;
                            }
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                    break;
                case $this::TASK_CONFIRM_IDENTITY:
                    $db_useragent = $linked_task->task_useragent;
                    $useragent = $_SERVER['HTTP_USER_AGENT'];

                    if($db_useragent == $useragent) {
                        $identity = User::USER_IDENTITY_CONFIRMED;
                        $this->db->query('UPDATE users SET identity = :confirmed WHERE user_id = :requester');
                        $this->db->bind(':confirmed', $identity);
                        $this->db->bind(':requester', $requester);
                        if($this->db->execute()) {
                            $_SESSION['identity_confirmed'] = $identity;
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                    break;
                case $this::TASK_RESET_PASSWORD:
                    $new_pw = $linked_task->task_temporary_pw;
                    $new_pass_hash = password_hash($new_pw, PASSWORD_DEFAULT);
                    $email = $requester;
                    $this->db->query('UPDATE users SET pass_hash = :hash WHERE user_email = :requester');
                    $this->db->bind(':hash', $new_pass_hash);
                    $this->db->bind(':requester', $email);
                    if($this->db->execute()) {
                        $template = new PasswordTemplate($new_pw, "Your new password: " . $new_pw . " (Please change it again as soon as possible!)");
                        sendEmail($email, SMTP_REPLY_TO, "Your temporary password", $template->getHtml(), $template->getAltMessage());
                        return true;
                    } else {
                        return false;
                    }
                default:
                    return false;
        }
    }

    /**
     * Create a new task
     *
     * @param $task_type
     * @param $requester
     * @param $data
     * @param null $altMail
     * @return bool
     */
    public function createNewTask($task_type, $requester, $data, $altMail=null) {
        $linked_task_id = uniqid('', true);
        $task_id = uniqid('', true);
        $current_time = time();
        $email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : $altMail;

        $this->db->query('INSERT INTO tasks (task_requested_by, task_requested_at, task_token, task_type, task_ref_id) VALUES (:requester, :currentTime, :taskToken, :taskType, :refId)');
        $this->db->bind(':taskToken', $task_id);
        $this->db->bind(':requester', $requester);
        $this->db->bind(':currentTime', $current_time);
        $this->db->bind(':taskType', $task_type);
        $this->db->bind(':refId', $linked_task_id);

        if($this->db->execute()) {
            switch ($task_type) {
                case $this::TASK_CONFIRM_PASSWORD:
                    $new_hash_pass = $data[0];
                    $this->db->query('INSERT INTO tasks_pw (task_id, task_new_hash) VALUES (:linkedId, :new_hash)');
                    $this->db->bind(':linkedId', $linked_task_id);
                    $this->db->bind(':new_hash', $new_hash_pass);
                    if($this->db->execute()) {
                        $template = New ConfirmTemplate("Your Password was changed",
                            "We need your confirmation!",
                            "Hey,<br>your password was recently changed!<br>Please confirm this change by pressing the confirmation<br>button below.",
                            URL_ROOT . "/tasks/confirm/{$task_id}/",
                            URL_ROOT . "/tasks/cancel/{$task_id}/",
                            "Your password was changed. Please confirm here " . URL_ROOT . "/tasks/confirm/{$task_id}/ " .
                            "or decline here " . URL_ROOT . "/tasks/cancel/{$task_id}/");
                        sendEmail($email,
                            SMTP_REPLY_TO,
                            "Confirm new password",
                            $template->getHtml(),
                            $template->getAltMessage());
                        return true;
                    } else {
                        return false;
                    }
                case $this::TASK_RESET_PASSWORD:
                    $temporary_pw = $data[0];
                    $this->db->query('INSERT INTO tasks_reset (task_id, task_temporary_pw) VALUES (:linkedId, :temp_pw)');
                    $this->db->bind(':linkedId', $linked_task_id);
                    $this->db->bind(':temp_pw', $temporary_pw);
                    if($this->db->execute()) {
                        $template = New ConfirmTemplate("Password Reset",
                            "We are going to reset your password",
                            "Hey,<br>you requested a reset of your password!<br>Please confirm this by pressing the confirm<br>button below.",
                            URL_ROOT . "/tasks/confirm/{$task_id}/",
                            URL_ROOT . "/tasks/cancel/{$task_id}/",
                            "We are going to reset your password. Please confirm here " . URL_ROOT . "/tasks/confirm/{$task_id}/ " .
                            "or decline here " . URL_ROOT . "/tasks/cancel/{$task_id}/");
                        sendEmail($email,
                            SMTP_REPLY_TO,
                            "Reset password",
                            $template->getHtml(),
                            $template->getAltMessage());
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case $this::TASK_CONFIRM_USERNAME:
                    $new_username = $data[0];
                    $this->db->query('INSERT INTO tasks_name (task_id, task_new_name) VALUES (:linkedId, :new_name)');
                    $this->db->bind(':linkedId', $linked_task_id);
                    $this->db->bind(':new_name', $new_username);
                    if($this->db->execute()) {
                        $template = New ConfirmTemplate("Your Username was changed",
                            "We need your confirmation!",
                            "Hey,<br>your username was recently changed to <strong>{$new_username}</strong>!<br>Please confirm this change by pressing the confirmation<br>button below.",
                            URL_ROOT . "/tasks/confirm/{$task_id}/",
                            URL_ROOT . "/tasks/cancel/{$task_id}/",
                            "Your password was changed. Please confirm here " . URL_ROOT . "/tasks/confirm/{$task_id}/ " .
                            "or decline here " . URL_ROOT . "/tasks/cancel/{$task_id}/");
                        sendEmail($email,
                            SMTP_REPLY_TO,
                            "Confirm new username",
                            $template->getHtml(),
                            $template->getAltMessage());
                        return true;
                    } else {
                        return false;
                    }
                case $this::TASK_CONFIRM_EMAIL:
                    $new_mail = $data[0];
                    $this->db->query('INSERT INTO tasks_mail (task_id, task_new_mail) VALUES (:linkedId, :new_mail)');
                    $this->db->bind(':linkedId', $linked_task_id);
                    $this->db->bind(':new_mail', $new_mail);
                    if($this->db->execute()) {
                        $template = New ConfirmTemplate("Your Email was changed",
                            "We need your confirmation!",
                            "Hey,<br>your mail address was recently changed to <strong>{$new_mail}</strong>!<br>Please confirm this change by pressing the confirmation<br>button below.",
                            URL_ROOT . "/tasks/confirm/{$task_id}/",
                            URL_ROOT . "/tasks/cancel/{$task_id}/",
                            "Your password was changed. Please confirm here " . URL_ROOT . "/tasks/confirm/{$task_id}/ " .
                            "or decline here " . URL_ROOT . "/tasks/cancel/{$task_id}/");
                        sendEmail($email,
                            SMTP_REPLY_TO,
                            "Confirm new email",
                            $template->getHtml(),
                            $template->getAltMessage());
                        return true;
                    } else {
                        return false;
                    }
                case $this::TASK_CONFIRM_IDENTITY:
                    $useragent = $data[0];
                    $this->db->query('INSERT INTO tasks_identity (task_id, task_useragent) VALUES (:linkedId, :useragent)');
                    $this->db->bind(':linkedId', $linked_task_id);
                    $this->db->bind(':useragent', $useragent);
                    if($this->db->execute()) {
                        $template = New ConfirmTemplate("Thanks for registration!",
                            "Please confirm your identity",
                            "Hey,<br>you are now signed up!<br>Please confirm your identity by pressing the confirmation<br>button below.",
                            URL_ROOT . "/tasks/confirm/{$task_id}/",
                            URL_ROOT . "/tasks/cancel/{$task_id}/",
                            "Your password was changed. Please confirm here " . URL_ROOT . "/tasks/confirm/{$task_id}/ " .
                            "or decline here " . URL_ROOT . "/tasks/cancel/{$task_id}/");
                        sendEmail($email,
                            SMTP_REPLY_TO,
                            "Confirm your identity",
                            $template->getHtml(),
                            $template->getAltMessage());
                        return true;
                    } else {
                        return false;
                    }
                default:
                    return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Remove task
     *
     * @param $task
     * @param null $linked_task
     * @return bool
     */
    public function removeTaskByToken($task, $linked_task=null) {
        if(!is_null($linked_task)) {
            $linked_task_id = $linked_task->task_id;
            $task_type = $task->task_type;
            //Delete associative task
            switch($task_type) {
                case $this::TASK_RESET_PASSWORD:
                    $this->db->query('DELETE FROM tasks_reset WHERE task_id = :linkedTask');
                    break;
                case $this::TASK_CONFIRM_PASSWORD:
                    $this->db->query('DELETE FROM tasks_pw WHERE task_id = :linkedTask');
                    break;
                case $this::TASK_CONFIRM_USERNAME:
                    $this->db->query('DELETE FROM tasks_name WHERE task_id = :linkedTask');
                    break;
                case $this::TASK_CONFIRM_EMAIL:
                    $this->db->query('DELETE FROM tasks_mail WHERE task_id = :linkedTask');
                    break;
                case $this::TASK_CONFIRM_IDENTITY:
                    $this->db->query('DELETE FROM tasks_identity WHERE task_id = :linkedTask');
                    break;
                default:
                    return false;
            }
            $this->db->bind(':linkedTask', $linked_task_id);
            if(!$this->db->execute()) {
                return false;
            }
        }

        //Delete main task entry
        $task_token = $task->task_token;
        $task_id = $task->task_id;
        $this->db->query('DELETE FROM tasks WHERE task_token = :token AND task_id = :id');
        $this->db->bind(':token', $task_token);
        $this->db->bind(':id', $task_id);
        return $this->db->execute();
    }

}