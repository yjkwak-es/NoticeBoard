<?php
include __DIR__ . "/idCheck.php";

$result = $admin->getallMembers();
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table,
        td {
            text-align: center;
            border-collapse: collapse;
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <table>
        <colgroup>
            <col width="20%" />
            <col width="20%" />
            <col width="30%" />
            <col width="20%" />
            <col width="*" />
        </colgroup>
        <thead>
            <tr>
                <th> TID </th>
                <th> ID </th>
                <th> Name </th>
                <th> Age </th>
                <th> Gender </th>
            </tr>
        </thead>
        <tbody>
            <? while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $row['PID'] ?></td>
                    <td><?= $row['ID'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['age'] ?></td>
                    <td><?= $row['gender'] ?></td>
                </tr>
            <? endwhile; ?>
        </tbody>
    </table>
</body>

</html>