<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
require_once('Z:\protected\userAuthentication.php');


final class userAuthenticationTest extends TestCase
{
    public function testIfProf(): void
    {
        $result = checkIfProfessor(1, "elk");
        $this->assertEquals(true, $result, "elk is not a student.");


    }
    public function testStudentNotProf(): void
    {
        $result = checkIfProfessor(1, "aaz143");
        $this->assertEquals(false, $result, "aaz143 is not a professor");
    }

}


?>