<?php

    /**
     * Brief
     *  Determine if the current student user
     *  The procedures of this function are as follows:
     *      - See if the student is in the current semeseter table in the database
     *          - Do nothing
     *      - Otherwise, search through previous semesters
     *          - Student found
     *              - Store semester
     *          - Student not found
     *              - Assume they are completely new
     *              - Add student to current semester's table within the database
     *
     * Input
     *  1) conn - Open connection to the database
     *
     * Output
     *  1) Semester in which the student belongs to
     */
    function handleAlumniIfNeeded($conn)
    {
        require_once("../generalFunctionality.php");

        $sem = getCurrentSemester($conn);
        $returnSemester = $sem;

        $currSemStatus = sprintf(
            "SELECT COUNT(1) FROM students_%s WHERE username REGEXP '^%s'",
            $sem, $_SERVER['REMOTE_USER']);

        $result = mysqli_query($conn, $currSemStatus);
        $inCurrSem = mysqli_fetch_assoc($result);

        if(!$inCurrSem["COUNT(1)"] && strcmp($_SERVER['REMOTE_USER'], "elk") !== 0)
        {
            $semesterFound = false;
            $getStudentTableNamesStr =
                "SELECT TABLE_NAME
                 FROM INFORMATION_SCHEMA.TABLES
                 WHERE TABLE_TYPE = 'BASE TABLE'
                 AND TABLE_NAME REGEXP '^students_';";

            $result = mysqli_query($conn, $getStudentTableNamesStr);

            while(!$semesterFound && $currSemTable = mysqli_fetch_assoc($result))
            {
                if(strcmp($currSemTable["TABLE_NAME"], $sem) !== 0)
                {
                    $getUsersInTable = sprintf(
                        "SELECT COUNT(1) FROM %s WHERE username REGEXP '^%s'",
                         $currSemTable["TABLE_NAME"], $_SERVER['REMOTE_USER']);

                    $semRes = mysqli_query($conn, $getUsersInTable);

                    $semesterFound = mysqli_fetch_assoc($semRes);
                    if($semesterFound["COUNT(1)"])
                    {
                        $returnSemester = getSemesterFromTableName($currSemTable["TABLE_NAME"]);
                    }
                }
            }
        }

        return $returnSemester;
    }

    /**
     * Brief
     *  Helper function to `handleAlumniIfNeeded()` to extract
     *  the semester from a table name
     *
     * Input
     *  1) tableName - Current table name, i.e., "students_<semester>"
     *
     * Output
     *  1) Semester contained within the given table name, e.g.,
     *     "students_Spring2024" => "Spring2024
     */
    function getSemesterFromTableName($tableName)
    {
        $splitStr = explode('_', $tableName);

        return $splitStr[1];
    }

?>
