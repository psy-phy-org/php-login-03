<?php
/**
 * Authentication class
 */
class Auth
{
    private $dbh = null;

    /**
     * constructor
     */
    public function __construct()
    {
        try {
            $this->dbh = new PDO(
                'mysql:host=localhost; dbname=login_03; charset=utf8',
                'root',
                'root',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            exit('ERR! : ' . $e->getMessage());
        } finally {
            $dbh = null;
        }
        session_start();
    }

    /**
     * Convert special characters to HTML entities.
     */
    public function h($str, string $charset = 'UTF-8'): string
    {
        return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, $charset);
    }

    /**
     * Hash password
     */
    public function hash()
    {
        return password_hash($_POST['upassword'], PASSWORD_DEFAULT);
    }

    /**
     * Sign up
     */
    public function register($uname, $upassword)
    {
        // 同じユーザー名がすでに登録されているかどうかを検索する
        // Search whether the same user name has already been registered.
        $sql = sprintf("SELECT COUNT(*) AS cnt FROM users WHERE uname='%s' ", $_POST['uname']);
        $sth = $this->dbh->query($sql);
        while ($row = $sth->fetch()) {
            $cnt = $row['cnt'];
            if ($cnt > 0) {
                $duplicate = true;
            } else {
                // パスワードをハッシュ化
                // Hash password
                $upassword = $this->hash($_POST['upassword']);
                // ユーザ名とパスワードを登録する
                // Register username and password
                $sth = $this->dbh->prepare('INSERT INTO users VALUES (?, ?)');
                $sth->bindParam(1, h($_POST['uname']), PDO::PARAM_STR);
                $sth->bindParam(2, h($upassword), PDO::PARAM_STR);
                return $sth->execute();
            }
        }
    }


    /**
     * Login
     */
    public function login($uname, $upassword)
    {
        $sth = $this->dbh->prepare('SELECT upassword FROM users WHERE uname = ?');
        $sth->bindParam(1, h($_POST['uname']), PDO::PARAM_STR);
        $sth->execute();
        // 検索結果の行数が1だったら
        // If the number of rows in the search result is 1.
        if ($sth->rowCount() == 1) {
            // 結果セットのupasswordカラムの値を$hashにバインド
            $sth->bindColumn('upassword', $hash);
            while ($sth->fetch()) {
                // upasswordカラムの値が$_POST['upassword']で取得したパスワードにマッチするかどうか
                if (password_verify($_POST['upassword'], $hash)) {
                    // セッションに$_POSTで取得したリクエストパラメータの値を保存
                    $_SESSION['user'] = $_POST;
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get login user
     */
    public function getUser()
    {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        return null;
    }

    /**
     * Logout
     */
    public function logout()
    {
        $_SESSION = array();
        session_destroy();
    }
}
