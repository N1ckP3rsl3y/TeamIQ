<?php
   // Professor username, change to Client after development
   global $adminUsers;
    $adminUsers = array("elk", "nsp73", "kvk23", "sdp243", "eez9", "lms737");

function userAuthenticator() {
    $isProf = checkIfProfessor();

    // if the user is not the client
    if (!$isProf)
    {
        // redirect to the student to their homepage
        header("Location: https://ac.nau.edu/redpen/protected/homepage.php");

        // exit the page
        exit;
    }
    }

function userRedirect() {
    $isProf = checkIfProfessor();

    // if the user is not the client
    if (!$isProf)
    {
        // redirect to the student to their homepage
        header("Refresh:2; URL=https://ac.nau.edu/redpen/protected/homepage.php");

    }

    // otherwise, the user is the client
    else
    {
        // redirect to professor homepage
        header("Refresh:2; URL=https://ac.nau.edu/redpen/protected/professor.php");

    }

}

function checkIfProfessor($mode = 0, $testName = array(""))
{
    // reference global professor name
    global $adminUsers;
    $currentCheckedAdmin = 0;
    $isProf = false;

    // mode 0 is general operation, mode 1 is testing
    if ($mode == 0)
    {
        $userName = $_SERVER['REMOTE_USER'];
    }
    else if ($mode == 1)
    {
        $userName = $testName;
    }

    for ($currentCheckedAdmin = 0; $currentCheckedAdmin < count($adminUsers); $currentCheckedAdmin++)
    {
        if (strcmp($userName, $adminUsers[$currentCheckedAdmin]) == 0)
        {
            $isProf = true;
        }

    }

    return $isProf;
}
?>
