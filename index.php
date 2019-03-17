<?php 
/**
 * *********************************************************************************************************
 * @_forProject: School-MaSS | Developed By: TAMMA CORPORATION
 * @_purpose: (authenticate school staff users) 
 * @_version Release: package_two
 * @_created Date: 3/15/2019
 * @_author(s):
 *   1) Mr. Michael kaiva Nimley. (Hercules)
 *      @contact Phone: (+231) 777-007-009
 *      @contact Mail: michaelkaivanimley.com@gmail.com, mnimley6@gmail.com, mnimley@tammacorp.com
 *   --------------------------------------------------------------------------------------------------
 *   2) Fullname of engineer. (Code Name)
 *      @contact Phone: (+231) 000-000-000
 *      @contact Mail: -----@tammacorp.com
 * *********************************************************************************************************
 */
require_once $_SERVER["DOCUMENT_ROOT"].'/schoolmass/config/config.inc.php';
$school_auth = new school_auth;

class school_auth
{
    private $schoolID; 
    private $StaffID; 
    private $result; 
    private $password; 

    public function __construct() {
        $this->AuthChecker();
        $this->schoolAuth();
    }

    // user has session. redirect to secure page 
    private function AuthChecker() {
        if ( !empty($_SESSION["schoolID"]) && !empty($_SESSION["StaffID"]) && !empty($_SESSION["userRights"]) ) {
            header("Location: /schoolmass/main/");
        } 
    } 

    // authenticate user
    private function schoolAuth() {
        if (isset($_POST['staff_login'])) {
            // sanitize provided credentials 
            $this->schoolID = io_stream::input($_POST['schoolID'], STRICT_INPUT_FILTER, IS_NULL);
            $this->StaffID  = io_stream::input($_POST['StaffID'], STRICT_INPUT_FILTER, IS_NULL);
            $this->password = io_stream::input($_POST['password'], STRICT_INPUT_FILTER, IS_NULL);

            //  run provided school id against db for match
            $MySql    =    db::$DBlink->query("SELECT * FROM all_clients WHERE School_ID = '$this->schoolID'") or die (db::$DBlink->error);
            // num of returned hits from query
            $School_ID_Match   =   $MySql->num_rows;
            // collect table names from all_clients
            $tableData   =   $MySql->fetch_assoc();
            // initialize security tbl to continue authentication
            $tbl_adminsecurity  =  $tableData['tbl_adminsecurity'];

            // school id matches
            if ($School_ID_Match > 0) {
                //  run provided staff id against db for match
                $MySql  =  db::$DBlink->query("SELECT * FROM `".$tbl_adminsecurity."` WHERE Staff_Id = '$this->StaffID' ") or die (db::$DBlink->error);

                // staff id matches
                if ($MySql->num_rows > 0) {
                    $securityData       =   $MySql->fetch_assoc();
                    // collect password from db
                    $password_from_db   =   $securityData['Password']; 
                    // collect user Access Right from db
                    $userAccessRight    =   $securityData['permissions'];

                    // compare password from db with user provided password 
                    $confirm_password   =   password_verify($this->password, $password_from_db);

                    // password matches
                    if ($confirm_password === 1) {
                        
                        // sessionlize table names of school to avoid constant db request
                        $_SESSION["schoolID"]                        =    $this->schoolID;
                        $_SESSION["StaffID"]                         =    $tableData["StaffID"];
                        $_SESSION["userRights"]                      =    $securityData['permissions'];
                        $_SESSION["SchoolName"]                      =    $tableData["Active_School"];
                        $_SESSION["tbl_applicant"]                   =    $tableData["tbl_applicant"];
                        $_SESSION["tbl_purgedApplicant"]             =    $tableData["tbl_purgedApplicant"];
                        $_SESSION["tbl_acceptedApplicant"]           =    $tableData["tbl_acceptedApplicant"];
                        $_SESSION["tbl_classes"]                     =    $tableData["tbl_classes"];
                        $_SESSION["tbl_subjects"]                    =    $tableData["tbl_subjects"];
                        $_SESSION["tbl_classToSubjects"]             =    $tableData["tbl_classToSubjects"];
                        $_SESSION["tbl_grades"]                      =    $tableData["tbl_grades"];
                        $_SESSION["tbl_staff"]                       =    $tableData["tbl_staff"];
                        $_SESSION["tbl_jobList"]                     =    $tableData["tbl_jobList"];
                        $_SESSION["tbl_departments"]                 =    $tableData["tbl_departments"];
                        $_SESSION["tbl_assignedCourses"]             =    $tableData["tbl_assignedCourses"];
                        $_SESSION["tbl_terms"]                       =    $tableData["tbl_terms"];
                        $_SESSION["tbl_registrationRequirements"]    =    $tableData["tbl_registrationRequirements"];
                        $_SESSION["tbl_registrationpayment"]         =    $tableData["tbl_registrationpayment"];
                        $_SESSION["tbl_registrationTotal"]           =    $tableData["tbl_registrationTotal"];
                        $_SESSION["tbl_tuitionPlan"]                 =    $tableData["tbl_tuitionPlan"];
                        $_SESSION["tbl_tuitionPayment"]              =    $tableData["tbl_tuitionPayment"];
                        $_SESSION["tbl_percentageForNewStud"]        =    $tableData["tbl_percentageForNewStud"];
                        $_SESSION["tbl_semesters"]                   =    $tableData["tbl_semesters"];
                        $_SESSION["tbl_schoolYear"]                  =    $tableData["tbl_schoolYear"];
                        $_SESSION["tbl_currentterm"]                 =    $tableData["tbl_currentterm"];
                        $_SESSION["tbl_currentsemester"]             =    $tableData["tbl_currentsemester"];
                        $_SESSION["tbl_classcapacity"]               =    $tableData["tbl_classcapacity"];
                        $_SESSION["tbl_school_grade_rules"]          =    $tableData["tbl_school_grade_rules"];
                        $_SESSION["tbl_entranceGradeRules"]          =    $tableData["tbl_entranceGradeRules"];
                        $_SESSION["tbl_entranceGraderule2"]          =    $tableData["tbl_entranceGraderule2"];
                        $_SESSION["tbl_allschoolyear"]               =    $tableData["tbl_allschoolyear"];
                        $_SESSION["tbl_classattendance"]             =    $tableData["tbl_classattendance"];
                        $_SESSION["tbl_levels"]                      =    $tableData["tbl_levels"];
                        $_SESSION["tbl_persmissions"]                =    $tableData["tbl_persmissions"];
                        $_SESSION["tbl_adminsecurity"]               =    $tableData["tbl_adminsecurity"];
                        $_SESSION["tbl_studentsecurity"]             =    $tableData["tbl_studentsecurity"];
                        $_SESSION["tbl_tbl_announcements"]           =    $tableData["tbl_tbl_announcements"];
                        $_SESSION["tbl_events"]                      =    $tableData["tbl_events"];
                        $_SESSION["tbl_honorlist"]                   =    $tableData["tbl_honorlist"];
                        $_SESSION["School_Class_Schedule"]           =    $tableData["School_Class_Schedule"];
                        $_SESSION["tuitionpayment_tbl_purge"]        =    $tableData["tuitionpayment_tbl_purge"];
                        $_SESSION["registrationpayment_tbl_purge"]   =    $tableData["registrationpayment_tbl_purge"];
                        $_SESSION["Internal_Transfer_Request"]       =    $tableData["Internal_Transfer_Request"];
                        $_SESSION["Transfered_Records"]              =    $tableData["Transfered_Records"];
                        $_SESSION["External_Transfer_Request"]       =    $tableData["External_Transfer_Request"];

                        // user is suspended or fired
                        if ($userAccessRight == "Suspended") {
                            $this->result = array(
                                'status' => false, 
                                'message' => 'This Account Has Been Suspended.', 
                                'action' => ''
                            );
                        }
                        // user is clear to proceed to secured page
                        else {
                            // check if user is signed in in another browser
                            $Active_User_Redundancy_Checker = db::$DBlink->query("SELECT * FROM `activeusers` 
                                WHERE SchoolID = '$this->schoolID' AND UserType = 'Staff' AND PersonID = '$this->StaffID'
                            ");
                            // User is signed in in another browser. do not re-record signin
                            if ($ActiveUserRedundancyChecker->num_rows > 0) { }
                            // User is not signed in anywhere else. record sign in
                            else {
                                $record_user_signin = db::$DBlink->query("INSERT INTO `activeusers`(SchoolID, UserType, PersonID)
                                    VALUES('$this->schoolID', 'Staff', '$this->StaffID')
                                ");

                                // redirect to homepage
                                $this->result = array(
                                    'status' => true, 
                                    'message' => 'Please Wait, Redirecting....', 
                                    'action' => '/schoolmass/main/'
                                );
                            }
                        }
                    }
                    // password does not match
                    else {
                        $this->result = array(
                            'status' => false, 
                            'message' => 'Password Is Invalid.', 
                            'action' => ''
                        );
                    }
                }
                // staff id does not match
                else {
                    $this->result = array(
                        'status' => false, 
                        'message' => 'Staff ID Is Invalid.', 
                        'action' => ''
                    );
                }
            }
            // school id did not match
            else {
                $this->result = array(
                    'status' => false, 
                    'message' => 'School ID Is Invalid.', 
                    'action' => ''
                );
            }

            print json_encode($this->result);
        }
    }
    
}

?>
