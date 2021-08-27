<?php
/**
 * Class Users Controller
 *
 * @author Ceytec <david@nani-games.net>
 * @author Nolan <nolan@nani-games.net>
 */
class Users extends Controller {

    /**
     * Reference Attribute for Task Model
     *
     * @var mixed
     */
    private $taskModel;

    /**
     * Reference Attribute for User Model
     *
     * @var mixed
     */
    private $userModel;

    /**
     * Google Authenticator Object
     *
     * @var \Sonata\GoogleAuthenticator\GoogleAuthenticator
     */
    private $googleAuth;

    /**
     * Users constructor.
     */
    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->taskModel = $this->model('Task');

        $this->googleAuth = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
    }

    /**
     * Handles user sessions
     *
     * @param $user
     */
    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['user_name'] = $user->user_name;
        $_SESSION['user_email'] = $user->user_email;
        $_SESSION['identity_confirmed'] = $user->identity;
        $_SESSION['session_created_at'] = time();
        unset($_SESSION['auth_user']);
    }

    /**
     * Logout logic
     */
    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        unset($_SESSION['identity_confirmed']);
        unset($_SESSION['session_created_at']);
        header('Location: ' . URL_ROOT . '/users/login');
    }

    /**
     * Reset Password Logic
     */
    public function reset() {
        $data = [
            'title' => 'Reset Password',
            'email' => '',
            'notFoundError' => '',
            'captchaError' => ''
        ];
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'title' => 'Reset Password',
                'email' => trim($_POST['email']),
                'notFoundError' => '',
                'captchaError' => ''
            ];

            //Check Captcha
            if(isset($_POST['captcha'])) {
                $captcha = $_POST['captcha'];
                $uid = $_POST['captchaId'];
                $response = file_get_contents("http://www.easycaptchas.com/check.aspx?sessionid=$uid&input=$captcha");
                if($response == "FALSE") {
                    $data['captchaError'] = 'Captcha is incorrect';
                }
            } else {
                $data['captchaError'] = 'Please answer captcha';
            }

            //Check for data
            if($_POST['email'] == "") {
                $data['notFoundError'] = 'Missing user data';
            }

            //Search user -> if existent -> send confirm mail -> after confirmation -> send temporary password
            if(empty($data['captchaError']) && empty($data['notFoundError'])) {
                $foundUser = $this->userModel->findUserByEmail($data['email']);
                if($foundUser->user_email == $data['email']) {
                    $new_pw = generateTempPassword();

                    //Search for existing task
                    $found_task = $this->taskModel->findTask($data['email'], Task::TASK_RESET_PASSWORD);
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
                    $this->taskModel->createNewTask(Task::TASK_RESET_PASSWORD, $data['email'], [$new_pw], $data['email']);
                } else {
                    $data['notFoundError'] = 'User was not found';
                }
            }
        }

        $this->view('users/reset_password', $data);
    }

    /**
     * Settings Logic
     */
    public function settings() {
        $settings = $this->userModel->getSettings($_SESSION['user_id']);

        $profile_wallpaper = $this->userModel->resolveSetting(User::USER_SETTING_PROFILE_WALLPAPERS, $settings->profile_wallpaper);
        $profile_picture = $this->userModel->resolveSetting(User::USER_SETTING_PROFILE_PICTURES, $settings->profile_picture);

        $available_wallpapers = $this->userModel->getOptions(User::USER_SETTING_PROFILE_WALLPAPERS);
        $available_pictures = $this->userModel->getOptions(User::USER_SETTING_PROFILE_PICTURES);

        $data = [
            'title' => 'Settings',
            'is_validated' => $this->userModel->isValidated($_SESSION['user_name']),
            'saved_two_auth' => $settings->two_auth,
            'saved_profile_picture' => $profile_picture,
            'saved_profile_wallpaper' => $profile_wallpaper,
            'options_profile_wallpapers' => $available_wallpapers,
            'options_profile_pictures' => $available_pictures,
            'saveSettingsError' => '',
            'saveSettingsConfirmation' => ''
        ];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            if(isLoggedIn()) {
                if(isset($_POST['code'])) {
                    if ($this->googleAuth->checkCode(TWO_AUTH_SECRET, $_POST['code'])) {
                        $this->userModel->connectedApp($_SESSION['user_name']);
                        $data['saveSettingsConfirmation'] = "Two factor authentication is now enabled";
                    } else {
                        $data['saveSettingsError'] = "Could not activate two factor authentication";
                    }
                } else {
                    $settings = [
                        'profile_picture' => isset($_POST['profile_picture']) ? $_POST['profile_picture'] : null,
                        'profile_wallpaper' => isset($_POST['profile_wallpaper']) ? $_POST['profile_wallpaper'] : null,
                        'two_auth' => isset($_POST['two_auth']) ? $_POST['two_auth'] : null
                    ];
                    if (!$this->userModel->updateSettings($settings, $_SESSION['user_id'])) {
                        $data['saveSettingsError'] = "Something went wrong";

                    } else {
                        $data['saveSettingsConfirmation'] = "Settings were successfully saved";
                        $data['saved_profile_picture'] = isset($_POST['saved_profile_picture']) ? $_POST['saved_profile_picture'] : $data['saved_profile_picture'];
                        $data['saved_profile_wallpaper'] = isset($_POST['saved_profile_wallpaper']) ? $_POST['saved_profile_wallpaper'] : $data['saved_profile_wallpaper'];
                        $data['saved_two_auth'] = isset($_POST['two_auth']) ? $_POST['two_auth'] : $data['saved_two_auth'];
                    }
                }
            }
        }
        if(isset($_POST['two_auth']) && $_POST['two_auth'] == TWO_AUTH_ACTIVE) {
            if(!$this->userModel->hasAppConnected($_SESSION['user_name'])) {
                $this->view('users/add_two_auth');
                return;
            }
        }
        $this->view('users/settings', $data);
    }

    /**
     * Login logic
     */
    public function login() {
        $data = [
            'title' => 'Login',
            'username' => '',
            'password' => '',
            'usernameError' => '',
            'passwordError' => ''
        ];
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            if(isset($_POST['code'])) {
                if(isset($_SESSION['auth_user'])) {
                    if($this->googleAuth->checkCode(TWO_AUTH_SECRET, $_POST['code'])) {
                        $loggedInUser = $_SESSION['auth_user'];
                        $this->createUserSession($loggedInUser);
                    } else {
                        $data['passwordError'] = 'Two factor authentication has failed';
                    }
                    $this->view('users/login', $data);
                    return;
                } else {
                    die('missing authentication user');
                }
            }
            $data = [
                'title' => 'Login',
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'usernameError' => '',
                'passwordError' => ''
            ];

            //Check if username is given
            if(empty($data['username'])) {
                $data['usernameError'] = "Please enter your username";
            }

            //Check if password is given
            if(empty($data['password'])) {
                $data['passwordError'] = "Please enter your password";
            }

            if(empty($data['usernameError']) && empty($data['passwordError'])) {
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);
                if($loggedInUser) {
                    $_SESSION['auth_user'] = $loggedInUser;
                    $settings = $this->userModel->getSettings($loggedInUser->user_id);
                    if($settings->two_auth == TWO_AUTH_ACTIVE) {
                        $this->view('users/code_required');
                        return;
                    } else {
                        $this->createUserSession($loggedInUser);
                    }
                } else {
                    $data['passwordError'] = 'Password or Username is wrong';
                    $this->view('users/login', $data);
                }
            }
        } else {
            $data = [
                'title' => 'Login',
                'username' => '',
                'email' => '',
                'usernameError' => '',
                'passwordError' => ''
            ];
        }
        $this->view('users/login', $data);
    }

    /**
     * Register logic
     */
    public function register() {
        $data = [
            'title' => 'Register',
            'username' => '',
            'email' => '',
            'password' => '',
            'confirmPassword' => '',
            'captchaError' => '',
            'usernameError' => '',
            'passwordError' => '',
            'emailError' => '',
            'confirmPasswordError' => ''
        ];
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'title' => 'Register',
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirmPassword' => trim($_POST['confirmPassword']),
                'captchaError' => '',
                'usernameError' => '',
                'passwordError' => '',
                'emailError' => '',
                'confirmPasswordError' => ''
            ];

            //Check Captcha
            if(isset($_POST['captcha'])) {
                $captcha = $_POST['captcha'];
                $uid = $_POST['captchaId'];
                $response = file_get_contents("http://www.easycaptchas.com/check.aspx?sessionid=$uid&input=$captcha");
                if($response == "FALSE") {
                    $data['captchaError'] = 'Captcha is incorrect';
                }
            } else {
                $data['captchaError'] = 'Please answer captcha';
            }

            //Validate Username
            $data['usernameError'] = validateUserName($data['username'], $this->userModel);

            //Validate Email
            $data['emailError'] = validateEmail($data['email'], $this->userModel);

            //Validate Password
            $data['passwordError'] = validatePassword($data['password']);

            //Validate Confirm Password
            $data['confirmPasswordError'] = validateConfirmPassword($data['password'], $data['confirmPassword']);

            //Register User
            if(empty($data['captchaError']) && empty($data['usernameError']) && empty($data['emailError']) && empty($data['passwordError']) && empty($data['confirmPasswordError'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                $new_id = uniqid('', true);
                if($this->userModel->register($data, $new_id)) {
                    //Create new confirmation task
                    $this->taskModel->createNewTask(Task::TASK_CONFIRM_IDENTITY, $new_id, [$_SERVER['HTTP_USER_AGENT']], $data['email']);
                    header('Location: ' . URL_ROOT . '/users/login');
                } else {
                    die('Something went wrong');
                }
            }
        }

        $this->view('users/register', $data);
    }

    /**
     * Account Page
     */
    public function account() {
        $data = [
            'title' => 'Account',
            'username' => $_SESSION['user_name']
        ];

        $this->view('users/account', $data);
    }

    /**
     * Fallback
     */
    public function index() {
        $data = ['title' => 'Home Page'];
        $this->view('pages/index', $data);
    }
}