<?php
class HelperFunctions {
    /*
     * @method - returnSortByResults
     * @description - to decide on the correct search function to call when displaying supplements
     * @param $sortBY- the option choosen in the drop down list - what search parameter - i.e. lowToHigh
     * @param $limitBY- the option choosen in the drop down list - number of results to return
     * @return $objArray - an array of Supplement objects
    */
    public static function returnSortByResults($sortBy, $limitBy) {
        $limitBy = (int)$limitBy;
        switch ($sortBy) {
            case 'pop':
                try {
                    return SupplementDB::getAllSupplementPopular($limitBy);
                } catch (Exception $ex) {
                    $error_message = $ex->getMessage();
                    require_once('error.php');
                    exit();
                }
                break;

            case 'id_asc':
                try {
                    return SupplementDB::getAllSupplementIDLowHigh($limitBy);
                } catch (Exception $ex) {
                    $error_message = $ex->getMessage();
                    require_once('error.php');
                    exit();
                }
                break;

            case 'id_desc':
                try {
                    return SupplementDB::getAllSupplementIDHighLow($limitBy);
                } catch (Exception $ex) {
                    $error_message = $ex->getMessage();
                    require_once('error.php');
                    exit();
                }
                break;

            case 'low':
                try {
                    return SupplementDB::getAllSupplementPriceLowHigh($limitBy);
                } catch (Exception $ex) {
                    $error_message = $ex->getMessage();
                    require_once('error.php');
                    exit();
                }
                break;

            case 'high':
                try {
                    return SupplementDB::getAllSupplementPriceHighLow($limitBy);
                } catch (Exception $ex) {
                    $error_message = $ex->getMessage();
                    require_once('error.php');
                    exit();
                }
                break;
        }
    }

    /*
     * @method - returnSortByResults
     * @description - to decide on the correct search function to call when displaying supplements
     * @param $sortBY- the option choosen in the drop down list - what search parameter - i.e. lowToHigh
     * @param $limitBY- the option choosen in the drop down list - number of results to return
     * @return $objArray - an array of Supplement objects
    */
    public static function returnSortByResultsBySupplier($sortBy, $limitBy, $id) {
        $limitBy = (int)$limitBy;
        $id = (int)$id;
        switch ($sortBy) {
            case 'pop':
                try {
                    return SupplementDB::getSupplementPopular($limitBy, $id);
                } catch (Exception $ex) {
                    $error_message = $ex->getMessage();
                    require_once('error.php');
                    exit();
                }
                break;

            case 'id_asc':
                try {
                    return SupplementDB::getSupplementIDLowHigh($limitBy, $id);
                } catch (Exception $ex) {
                    $error_message = $ex->getMessage();
                    require_once('error.php');
                    exit();
                }
                break;

            case 'id_desc':
                try {
                    return SupplementDB::getSupplementIDHighLow($limitBy, $id);
                } catch (Exception $ex) {
                    $error_message = $ex->getMessage();
                    require_once('error.php');
                    exit();
                }
                break;

            case 'low':
                try {
                    return SupplementDB::getSupplementPriceLowHigh($limitBy, $id);
                } catch (Exception $ex) {
                    $error_message = $ex->getMessage();
                    require_once('error.php');
                    exit();
                }
                break;

            case 'high':
                try {
                    return SupplementDB::getSupplementPriceHighLow($limitBy, $id);
                } catch (Exception $ex) {
                    $error_message = $ex->getMessage();
                    require_once('error.php');
                    exit();
                }
                break;
        }
    }

    /**
     * @method - returnTextClass($status)
     * @description - to decide on what css class string should have
     * @param $status  string - the status of of the transaction
     * @return - string - the appropriate class
    */
    public static function returnTextClass($status) {
        $statusClass = "";

        switch ($status) {
            case 'APPROVED':
            case 'SHIPPED':
                $statusClass = 'green-text';
                break;
            case 'CANCELED':
                $statusClass = 'red-text';
                break;
            case 'PENDING':
                $statusClass = 'amber-text';
                break;
        }

        return $statusClass;
    }

    /**
     * @method - restrictUserAccess($jobID)
     * @description - restrict user access depending on user job role. Depenindg on job ID provide the user with a different naviagation bar
     * @param - $jobID - an employee job id
     * @return - string - the navigation bar path
    */
    public static function restrictUserAccess($jobID) {
        /*
         * job ID's are as follows
         * 1 = HCP
         * 2 = GA
         * 3 = superuser
         */
        $path = "";
        switch ($jobID) {
            case '1':
                $path = '../view/nav_admin_hcp.inc';
                return $path;
                break;
            case '2':
                $path = '../view/nav_admin_ga.inc';
                return $path;
                break;
            case '3':
                $path = '../view/nav_admin.inc';
                return $path;
                break;
            default:
                // fallback to GA as this is the least access
                // could of not included case 2 in code - but did for clarity
                $path = '../view/nav_admin_ga.inc';
                return $path;
                break;
        }
    }

} // end class

?>
