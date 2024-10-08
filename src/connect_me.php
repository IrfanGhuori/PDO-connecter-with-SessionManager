<?php
namespace Src;

class Connect_me
{
    protected $Hoster = null;
    protected $Port = null;
    protected $User = null;
    protected $Password = null;
    protected $DB = null;
    protected $Server_vars = null;
    protected $DataType = null;
    public $conn = null;
    public $ErrorArray = null;

    public function __construct()
    {
        $this->db_connects();
    }
    private function db_connects()
    {
        try {
            $this->CollectData();

            $this->ErrorArray = array(
                /**
                 * Unknown database - 1049
                 * Depending on how MySQL was installed, it is possible that the default MySQL database was NOT created. 
                 * This may be checked by looking in /var/lib/mysql for a mysql subfolder
                 * (i.e. /var/lib/mysql/mysql ). If the path does NOT contain a mysql subfolder,
                 * it needs to be created
                 */
                "1049" => " <strong>Database</strong> not found",

                /**
                 *Access denied - 1045
                 * MySQL provides a privilege system that authenticates the user
                 * who connects from a host, and associates the user with access 
                 * privileges on a database. The privileges include SELECT, INSERT, UPDATE,
                 * and DELETE and are able to identify anonymous users and grant privileges 
                 * for MySQL specific functions, such as LOAD DATA INFILE and administrative 
                 * operations. The access denied error may occur because of many causes.
                 * In many cases, the problem is caused because of MySQL accounts that the 
                 * client programs use to connect with the MySQL server with permission 
                 * from the server.
                 */
                "1045" => " <strong>username or password </strong> incorrect",

                /**
                 * hostname not found - 2002
                 * The MySQL hostname will always be ‘localhost’ 
                 * in your configuration files. If you need to 
                 * connect to your database from your
                 */
                "2002" => " Please confirm web <strong>hostname</strong>",

                /**
                 * Letters are exceeding to the limit - 42000
                 * The ERROR 1064 (42000) mainly occurs when the syntax 
                 * isn’t set correctly i.e. error in applying 
                 * the backtick symbol or while creating a database 
                 * without them can also create an error, 
                 * if you will use hyphen in the name, for example, 
                 * Demo-Table will result in ERROR 1064 (42000).
                 */
                "42000" => " Letters are exceeding to the limit",

                /**
                 * Duplicate answer - 42S21
                 * Please note that views must have unique column names with no duplicates. 
                 * By default, the names of the columns retrieved by the SELECT 
                 * statement are used for the view column names. As your 
                 * tables in SELECT statement contain some column
                 * names which are the same, therefore it results in 
                 * violation and throws an error.
                 */
                "42S21" => " Duplicate answer",

                /**Avoid the problem by refining your queries - 2013
                 *In many cases, you can avoid the problem entirely 
                 * by refining your SQL queries. For example, 
                 * instead of joining all the contents of two 
                 * very large tables, try filtering out the records
                 * you don’t need. Where possible, try reducing the
                 * number of joins in a single query. This should have 
                 * the added benefit of making your query easier to read. 
                 * For my purposes, I’ve found that denormalizing 
                 * content into working tables can improve the 
                 * read performance. This avoids time-outs.
                 * Re-writing the queries isn’t always option so you can 
                 * try the following server-side and
                 * client-side workarounds. */
                "2013" => " <strong>Lost connection </strong> to MySQL server during query",

                /**On some systems, you may find that your password  - 1524
                 * works when specified in an option file or on 
                 * the command line, but not when you enter 
                 * it interactively at the Enter password: prompt. 
                 * This occurs when the library provided by 
                 * the system to read passwords limits password 
                 * values to a small number of characters (typically eight).
                 * That is a problem with the system library, not with MySQL.
                 * To work around it, change your MySQL password to a value that is 
                 * eight or fewer characters long, or put 
                 * your password in an option file. */
                "1524" => " <strong>Password fails </strong> when entered incorrectly",

                /** The number of connections permitted is controlled by -  1129
                 * the MySQL/MariaDB max_connections system variable. 
                 * The default value is 151 to improve performance when 
                 * MySQL is used with the Web server. You might run into 
                 * a problem if you are running a high trafficked web site or
                 * MariaDB server in clustered mode or using a Galera master to master 
                 * DB cluster. If you need to support more connections,
                 */
                "1129" => " Host <strong>'host_name' is blocked </strong> because of many connection",

                /** Out of memory - 2008
                 * To remedy the problem first check whether your query is correct. 
                 * Is it reasonable that it should return so many rows? 
                 * If not, correct the query and try again. Otherwise, 
                 * you can invoke mysql with the --quick option. This causes
                 * it to use the mysql_use_result() C API function to retrieve the result set,
                 * which places less of a load on the client (but more on the server). 
                 * */
                "2008" => " MySQL client ran <strong> out of memory </strong>",

                /**
                 * Packet too large - 2027
                 * It is safe to increase the value of this variable because 
                 * the extra memory is allocated only when needed. For example,
                 * mysqld allocates more memory only when you 
                 * issue a long query or when mysqld must return a large result row. 
                 */
                "2027" => " Packet <strong> too large </strong>",

                /**
                 * The table is full - 1114
                 * If a table-full error occurs, it may be that the 
                 * disk is full or that the table has reached its 
                 * maximum size. The effective maximum table size for MySQL 
                 * databases is usually determined by operating system 
                 * constraints on file sizes, not by MySQL internal limits.
                 */
                "1114" => " The table is <strong> full </strong>",

                /**
                 * Commands out of sync - 2014
                 * If you get Commands out of sync; you can't 
                 * run this command now in your client code, 
                 * you are calling client functions in the wrong order.
                 * This can happen, for example, if you are using 
                 * mysql_use_result() and try to execute a new query 
                 * before you have called mysql_free_result(). It can also 
                 * happen if you try to execute two queries that return data 
                 * without calling mysql_use_result() or mysql_store_result() 
                 * in between.
                 */
                "2014" => " Commands <strong> out of sync </strong>",

                /**
                 * Ignoring user - 00000
                 * If you get the following error, it means that when mysqld was 
                 * started or when it reloaded the grant tables, it found an account 
                 * in the user table that had an invalid password.
                 * Found wrong password for user 'some_user'@'some_host'; ignoring user
                 * As a result, the account is simply 
                 * ignored by the permission system. 
                 * To fix this problem, assign a new, valid password to the account. */
                "00000" => " <strong> Database </strong> Ignoring user",

                /**
                 * Table 'tbl_name' doesn't exist - 1146
                 * In some cases, it may be that the table does exist 
                 * but that you are referring to it incorrectly:
                 * Because MySQL uses directories and files to 
                 * store databases and tables, database and table 
                 * names are case sensitive if they are located on a 
                 * file system that has case-sensitive file names.
                 * Even for file systems that are not case-sensitive, 
                 * such as on Windows, all references to a given table
                 *  within a query must use the same lettercase. 
                 */
                "1146" => " Table tbl_name <strong> doesn’t exist </strong>"
            );
            print_r($this->Server_vars);
            $this->conn = new \PDO($this->DataType . ':host=' . $this->Hoster . ';dbname=' . $this->DB, $this->User, $this->Password);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            /*** echo a message saying we have connected ***/
            //echo 'Connected to database'; // Test with this string

        } catch (\PDOException $error) {
            /** Detect error from the connecting process */
            $dx = $error->getCode(); //  Detect databse error 
            /** search error code from the array  */
            foreach ($this->ErrorArray as $codes => $string) {
                if ($dx == $codes):
                    echo $string;
                endif;
            }
        }
        return $this->conn;
    }
    public static function getEnv($key, $default = null, $filter = null, $options = null)
    {
        if (false !== $env = \getenv($key)) {
            return static::prepareValue($env, $filter, $options);
        }

        if (isset($_ENV[$key])) {
            return static::prepareValue($_ENV[$key], $filter, $options);
        }

        if (isset($_SERVER[$key])) {
            return static::prepareValue($_SERVER[$key], $filter, $options);
        }

        // Default is not passed through filter!
        return $default;
    }

    protected static function prepareValue($env, $filter, $options)
    {
        static $special = [
        'true' => true,
        'false' => false,
        'null' => null,
        'TRUE' => true,
        'FALSE' => false,
        'NULL' => null,
        ];

        // strlen($env) < 6.
        if (!isset($env[5]) && \array_key_exists($env, $special)) {
            return $special[$env];
        }

        if ($filter === null || !\function_exists('filter_var')) {
            return $env;
        }

        return \filter_var($env, $filter, $options);
    }
    private function CollectData()
    {
        /** filter all Operation */
        require_once 'joiner.php';
        /** Join All classess */
        Joiner::JoinInternal('helper');
        /** Create classe dynamically  */
        Joiner::CreateClass('helper');
        /** find Configration files */
        Helper::_CreateVars();
        /** Collect Config data from client side */
        $this->Hoster = $_ENV['DB_HOST'];
        $this->DB = $_ENV['DB_DATABASE'];
        $this->User = $_ENV['DB_USERNAME'];
        $this->Password = $_ENV['DB_PASSWORD'];
        $this->Port = $_ENV['DB_PORT'];
        $this->DataType = $_ENV['DB_CONNECTION'];
    }

    private function _ConnectFiles($files)
    {
        if (file_exists($files)) {
            if (defined('ConnectFileWith1421Areasession')) {
                return $files;
            } else {
                header_remove();
                header('Location : /404.html');
                die();
            }
        }
    }


}

