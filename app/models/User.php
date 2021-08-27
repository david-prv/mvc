<?php
/**
 * Class User Model
 *
 * @author Ceytec <david@nani-games.net>
 */
class User {
    private $db;

    /**
     * Constant for global user identity management
     *
     * Use this for confirmed identities
     */
    const USER_IDENTITY_CONFIRMED = "confirmed";

    /**
     * Constant for global user identity management
     *
     * Use this for unconfirmed identities
     */
    const USER_IDENTITY_UNCONFIRMED = "pending";

    /**
     * Constant for setting classification
     *
     * Use this for profile picture settings
     */
    const USER_SETTING_PROFILE_PICTURES = "settings_profile_picture";

    /**
     * Constant for setting classification
     *
     * Use this for wallpaper settings
     */
    const USER_SETTING_PROFILE_WALLPAPERS = "settings_profile_wallpapers";

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Get all users
     *
     * @return array
     */
    public function getUsers() {
        $this->db->query("SELECT * FROM users");
        return $this->db->resultSet();
    }

    /**
     * Returns if user's identity is
     * validated or not
     *
     * @param $user_name
     * @return bool
     */
    public function isValidated($user_name) {
        $user = $this->findUserByName($user_name);
        return ($user->identity == $this::USER_IDENTITY_CONFIRMED) ? true : $user->registration_date;
    }

    /**
     * Returns if user has connected google
     * authenticator
     *
     * @param $user_name
     * @return bool
     */
    public function hasAppConnected($user_name) {
        $user = $this->findUserByName($user_name);
        return ($user->google_auth == TWO_AUTH_APP_CONNECTED);
    }

    /**
     * Note App Connection as active
     *
     * @param $user_name
     * @return bool
     */
    public function connectedApp($user_name) {
        $auth = TWO_AUTH_APP_CONNECTED;
        $this->db->query('UPDATE users SET google_auth = :auth WHERE user_name = :name');
        $this->db->bind(':auth', $auth);
        $this->db->bind(':name', $user_name);
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Inserts new setting information for new user
     *
     * @param $settings
     * @param $user_id
     * @return bool
     */
    public function insertSettings($settings, $user_id) {
        $pb = !(is_null($settings['profile_picture'])) ? $settings['profile_picture'] : STANDARD_PROFILE_PICTURE;
        $wp = !(is_null($settings['profile_wallpaper'])) ? $settings['profile_wallpaper'] : STANDARD_PROFILE_WALLPAPER;
        $twoAuth = !(is_null($settings['two_auth'])) ? $settings['two_auth'] : STANDARD_TWO_AUTH;

        $this->db->query('INSERT INTO settings (user_id, profile_picture, profile_wallpaper, two_auth) VALUES (:id, :profile_pic, :profile_wp, :auth)');
        $this->db->bind(':id', $user_id);
        $this->db->bind(':profile_pic', $pb);
        $this->db->bind(':profile_wp', $wp);
        $this->db->bind(':auth', $twoAuth);

        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Updates settings
     *
     * @param $settings
     * @param $user_id
     * @return bool|object
     */
    public function updateSettings($settings, $user_id) {
        $saved_settings = $this->getSettings($user_id);

        $pb = !(is_null($settings['profile_picture'])) ? $settings['profile_picture'] : $saved_settings->profile_picture;
        if(!$this->resolveSetting(User::USER_SETTING_PROFILE_PICTURES, $pb)) $pb = $saved_settings->profile_picture;
        $wp = !(is_null($settings['profile_wallpaper'])) ? $settings['profile_wallpaper'] : $saved_settings->profile_wallpaper;
        if(!$this->resolveSetting(User::USER_SETTING_PROFILE_WALLPAPERS, $wp)) $wp = $saved_settings->profile_wallpaper;
        $twoAuth = !(is_null($settings['two_auth'])) ? $settings['two_auth'] : $saved_settings->two_auth;
        if($twoAuth !== TWO_AUTH_ACTIVE && $twoAuth !== TWO_AUTH_INACTIVE) $twoAuth = $saved_settings->two_auth;

        $this->db->query('UPDATE settings SET profile_picture = :profile_pic, profile_wallpaper = :profile_wp, two_auth = :auth WHERE user_id = :id');
        $this->db->bind(':id', $user_id);
        $this->db->bind(':profile_pic', $pb);
        $this->db->bind(':profile_wp', $wp);
        $this->db->bind(':auth', $twoAuth);

        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns user settings by id
     *
     * @param $user_id
     * @return bool|object
     */
    public function getSettings($user_id) {
        $this->db->query('SELECT * FROM settings WHERE user_id = :id');
        $this->db->bind(':id', $user_id);
        $row = $this->db->single();
        if($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Resolve linked setting id
     *
     * @param $setting_type
     * @param $setting_id
     * @return bool|object
     */
    public function resolveSetting($setting_type, $setting_id) {
        switch($setting_type) {
            case User::USER_SETTING_PROFILE_PICTURES:
                $this->db->query('SELECT * FROM settings_profile_pictures WHERE picture_id = :setting_id');
                $this->db->bind(':setting_id', $setting_id);
                break;
            case User::USER_SETTING_PROFILE_WALLPAPERS:
                $this->db->query('SELECT * FROM settings_profile_wallpapers WHERE wallpaper_id = :setting_id');
                $this->db->bind(':setting_id', $setting_id);
                break;
            default:
                die('Unknown setting type');
        }
        $row = $this->db->single();
        if($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Get all possible options of settings
     * which are saved in mapped database table
     *
     * @param $setting_type
     * @return array|bool
     */
    public function getOptions($setting_type) {
        switch($setting_type) {
            case User::USER_SETTING_PROFILE_WALLPAPERS:
                $this->db->query('SELECT * FROM settings_profile_wallpapers');
                break;
            case User::USER_SETTING_PROFILE_PICTURES:
                $this->db->query('SELECT * FROM settings_profile_pictures');
                break;
            default:
                die('Unknown setting type');
        }
        $result = $this->db->resultSet();
        if($this->db->rowCount() > 0) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Registers a new user
     *
     * @param $data
     * @param $user_id
     * @return bool
     */
    public function register($data, $user_id) {
        $identity = User::USER_IDENTITY_UNCONFIRMED;
        $date = time();
        $google_auth = TWO_AUTH_APP_DISCONNECTED;
        $this->db->query('INSERT INTO users (user_id, user_name, user_email, pass_hash, identity, registration_date, google_auth) VALUES (:user_id, :user_name, :user_email, :pass_hash, :pending, :date, :google_auth)');
        $this->db->bind(':user_name', $data['username']);
        $this->db->bind(':user_email', $data['email']);
        $this->db->bind(':pass_hash', $data['password']);
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':pending', $identity);
        $this->db->bind(':date', $date);
        $this->db->bind(':google_auth', $google_auth);

        if($this->db->execute()) {
            if($this->insertSettings([], $user_id)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Logs in an existing user
     *
     * @param $username
     * @param $password
     * @return bool|object
     */
    public function login($username, $password) {
        $this->db->query('SELECT * FROM users WHERE user_name = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();
        $hashedPass = $row->pass_hash;
        if(password_verify($password, $hashedPass)) {
            return $row;
        } else {
            return false;
        }

    }

    /**
     * Find user by email address
     *
     * @param $email
     * @return bool|object
     */
    public function findUserByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE user_email = :email");
        $this->db->bind(":email", $email);
        $row = $this->db->single();
        if($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Finds user by name
     *
     * @param $username
     * @return bool|object
     */
    public function findUserByName($username) {
        $this->db->query("SELECT * FROM users WHERE user_name = :username");
        $this->db->bind(":username", $username);
        $row = $this->db->single();
        if($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Finds user by id
     *
     * @param $user_id
     * @return bool|object
     */
    public function findUserById($user_id) {
        $this->db->query("SELECT * FROM users WHERE user_id = :id");
        $this->db->bind(":id", $user_id);
        $row = $this->db->single();
        if($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

}