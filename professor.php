<!DOCTYPE html>

<html>
    <header>
        <link rel="stylesheet" href="professor.css" />
    </header>

    <body>

    <h2>Results</h2>
    <table>
        <tr>
            <th> Question </th>
            <th> # Attempts </th>
        </tr>

        <?php
        $server = "mysql.ac.nau.edu";
        $user = "redpenweb";
        $password = "teamiq2023!?";
        $database = "redpenweb";

        $conn = mysqli_connect($server, $user, $password, $database);

        if(!$conn)
        {
            echo "Something went wrong when connecting to the database";
        }

        $sql = "SELECT question, attempts FROM test_table;";

        $res = mysqli_query($conn, $sql);

        while($row = mysqli_fetch_assoc($res))
        {
            echo "<tr>";

            echo "<td>";
            echo $row["question"];
            echo "</td>";

            echo "<td class='attempt'>";
            echo $row["attempts"];
            echo "</td>";

            echo "</tr>";
        }

        ?>
    </table>

    </body>
</html>
