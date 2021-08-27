<?php
/**
 * Class Tasks Controller
 *
 * @author Ceytec <david@nani-games.net>
 */
class Tasks extends Controller {

    /**
     * Reference Attribute to Task Model
     *
     * @var mixed
     */
    private $taskModel;

    /**
     * Reference Attribute to User Model
     *
     * @var mixed
     */
    private $userModel;

    /**
     * Tasks constructor.
     */
    public function __construct()
    {
        $this->taskModel = $this->model('Task');
        $this->userModel = $this->model('User');
    }

    /**
     * Creates a new task
     *
     * @param $param
     */
    public function create($param=null) {
        $task_token = $param;
        /*
         * Data wont get passed through $data since data has to be
         * dynamically changed
         */
        $data = [
            'taskError' => null
        ];
        if(!isLoggedIn()) {
            die('You are not logged in');
        } else {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            if (!is_null($task_token)) {
                switch ($task_token) {
                    case Task::TASK_CONFIRM_EMAIL:
                        $newMail = $_POST['newMail'];
                        $confirmMail = $_POST['confirmMail'];

                        //Validate new Email
                        $data['taskError'] = validateEmail($newMail, $this->userModel);

                        //Validate confirmMail
                        $data['taskError'] = empty($data['taskError']) ? validateConfirmEmail($newMail, $confirmMail) : $data['taskError'];

                        if (empty($data['taskError'])) {
                            //Search for existing task
                            $found_task = $this->taskModel->findTask($_SESSION['user_id'], Task::TASK_CONFIRM_EMAIL);
                            var_dump($found_task);
                            if($found_task) {
                                $linked_task = $this->taskModel->findLinkedTask($found_task->task_ref_id, $found_task->task_type);
                                if(!$linked_task) {
                                    $data['taskError'] = 'Illegal Task Structure of existing Task';
                                } else {
                                    if(!$this->taskModel->removeTaskByToken($found_task, $linked_task)) {
                                        $data['taskError'] = 'Removal of existing Task failed';
                                    }
                                }
                            }

                            //Create new
                            $this->taskModel->createNewTask(Task::TASK_CONFIRM_EMAIL, $_SESSION['user_id'], [$newMail]);
                        }
                        break;
                    case Task::TASK_CONFIRM_USERNAME:
                        $newUsername = $_POST['newName'];

                        //Validate Username
                        $data['taskError'] = validateUserName($newUsername, $this->userModel);

                        if (empty($data['taskError'])) {
                            //Search for existing task
                            $found_task = $this->taskModel->findTask($_SESSION['user_id'], Task::TASK_CONFIRM_USERNAME);
                            if($found_task) {
                                $linked_task = $this->taskModel->findLinkedTask($found_task->task_ref_id, $found_task->task_type);
                                if(!$linked_task) {
                                    $data['taskError'] = 'Illegal Task Structure of existing Task';
                                } else {
                                    if(!$this->taskModel->removeTaskByToken($found_task, $linked_task)) {
                                        $data['taskError'] = 'Removal of existing Task failed';
                                    }
                                }
                            }

                            //Create new task
                            $this->taskModel->createNewTask(Task::TASK_CONFIRM_USERNAME, $_SESSION['user_id'], [$newUsername]);
                        }
                        break;
                    case Task::TASK_CONFIRM_PASSWORD:
                        $oldPass = $_POST['oldPassword'];
                        $newPass = $_POST['newPassword'];
                        $confirmPassword = $_POST['confirmPassword'];

                        //Validate Password
                        $data['taskError'] = validatePassword($newPass);
                        $data['taskError'] = validateConfirmPassword($newPass, $confirmPassword);
                        if (!$this->userModel->login($_SESSION['user_name'], $oldPass)) {
                            $data['taskError'] = 'Unknown credentials: ' . $_SESSION['user_name'] . " " . $oldPass;
                        }

                        if (empty($data['taskError'])) {
                            //Search for existing task
                            $found_task = $this->taskModel->findTask($_SESSION['user_id'], Task::TASK_CONFIRM_PASSWORD);
                            if($found_task) {
                                $linked_task = $this->taskModel->findLinkedTask($found_task->task_ref_id, $found_task->task_type);
                                if(!$linked_task) {
                                    $data['taskError'] = 'Illegal Task Structure of existing Task';
                                } else {
                                    if(!$this->taskModel->removeTaskByToken($found_task, $linked_task)) {
                                        $data['taskError'] = 'Removal of existing Task failed';
                                    }
                                }
                            }

                            //Create new task
                            $this->taskModel->createNewTask(Task::TASK_CONFIRM_PASSWORD, $_SESSION['user_id'], [$newPass]);
                        }
                        break;
                    case Task::TASK_CONFIRM_IDENTITY:
                        $useragent = $_SERVER['HTTP_USER_AGENT'];
                        //Check confirmation
                        if($_SESSION['identity_confirmed'] == User::USER_IDENTITY_CONFIRMED) {
                            $data['taskError'] = 'Identity is already confirmed';
                        }
                        if (empty($data['taskError'])) {
                            //Search for existing task
                            $found_task = $this->taskModel->findTask($_SESSION['user_id'], Task::TASK_CONFIRM_IDENTITY);
                            if($found_task) {
                                $linked_task = $this->taskModel->findLinkedTask($found_task->task_ref_id, $found_task->task_type);
                                if(!$linked_task) {
                                    $data['taskError'] = 'Illegal Task Structure of existing Task';
                                } else {
                                    if(!$this->taskModel->removeTaskByToken($found_task, $linked_task)) {
                                        $data['taskError'] = 'Removal of existing Task failed';
                                    }
                                }
                            }

                            //Create new task
                            $this->taskModel->createNewTask(Task::TASK_CONFIRM_IDENTITY, $_SESSION['user_id'], [$useragent]);
                        }
                        break;
                    default:
                        $data['taskError'] = 'Unknown task type';
                }
            } else {
                $data['taskError'] = 'Missing task type';
            }
            if(!is_null($data['taskError'])) {
                $this->view('tasks/task_error', $data);
            } else {
                $this->view('tasks/task_created', $data);
            }
        }
    }

    /**
     * Email task cancellation
     *
     * @param $param
     */
    public function cancel($param=null) {
        $task_token = $param;
        /*
         * Data wont get passed through $data since data has to be
         * dynamically changed
         */
        $data = [
            'taskError' => null
        ];

        if(!is_null($task_token)) {
            //Search for task in database
            $found_task = $this->taskModel->findTaskByToken($task_token);
            //Check if task exists and confirm requester
            if($found_task) {
                $linked_task = $this->taskModel->findLinkedTask($found_task->task_ref_id, $found_task->task_type);
                if(!$linked_task) {
                    $data['taskError'] = 'Illegal task structure';
                } else {
                    if(!$this->taskModel->removeTaskByToken($found_task, $linked_task)) {
                        $data['taskError'] = 'Task cancellation failed';
                    }
                }
            } else {
                $data['taskError'] = 'Task was already deleted or a new one was created';
            }
        } else {
            $data['taskError'] = 'Missing task';
        }
        if(!is_null($data['taskError'])) {
            $this->view('tasks/task_error', $data);
        } else {
            $this->view('tasks/task_cancelled', $data);
        }
    }

    /**
     * Email task confirmation
     *
     * @param $param
     */
    public function confirm($param=null) {
        $task_token = $param;
        /*
         * Data wont get passed through $data since data has to be
         * dynamically changed
         */
        $data = [
            'taskError' => null
        ];

        if(!is_null($task_token)) {
            //Search for task in database
            $found_task = $this->taskModel->findTaskByToken($task_token);
            //Check if task exists and confirm requester
            if($found_task) {
                try {
                    $task_created_at = new DateTime();
                    $task_created_at->setTimestamp($found_task->task_requested_at);
                    $current_time = new DateTime();
                    $diff = $task_created_at->diff($current_time);
                    $diff = (int)$diff->format('%d');
                    if($diff >= TASK_EXPIRATION_TIME) {
                        $data['taskError'] = 'This task is too far back';
                    }
                } catch (Exception $e) {
                    die('Critical error ' . $e);
                }
                $linked_task = $this->taskModel->findLinkedTask($found_task->task_ref_id, $found_task->task_type);
                if(!$linked_task) {
                    $data['taskError'] = 'Something went wrong';
                } else {

                    if($this->taskModel->executeTask($found_task->task_type, $found_task->task_requested_by, $linked_task)) {
                        if(!$this->taskModel->removeTaskByToken($found_task, $linked_task)) {
                            $data['taskError'] = 'Task deletion failed';
                        }
                    } else {
                        $data['taskError'] = 'Task failed';
                    }
                }
            } else {
                $data['taskError'] = 'Task was already deleted or a new one was created';
            }
        } else {
            $data['taskError'] = 'Missing task';
        }
        if(!is_null($data['taskError'])) {
            $this->view('tasks/task_error', $data);
        } else {
            $this->view('tasks/task_confirmed', $data);
        }
    }

    /**
     * Fallback
     */
    public function index() {
            $data = ['title' => 'Home Page'];
            $this->view('pages/index', $data);
        }
    }