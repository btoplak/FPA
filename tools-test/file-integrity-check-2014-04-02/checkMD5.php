<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <title></title>
    </head>
    <body>
        <?php
        include 'libraries/integrity.md5.class.php';
        $integrity = new integrity('path/to/check/directory');
        $files = $integrity->checkMD5Hashes('hash.md5');
        ?>
        <h2>Modified Files</h2>
        <table>
            <thead>
                <tr>
                    <th>File</th>
                    <th>State</th>
                    <th>User ID</th>
                    <th>Group ID</th>
                    <th>Last Access</th>
                    <th>Last Modified</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($files as $file) { ?>
                <tr>
                    <td><?php echo $file['filename']; ?></td>
                    <td><?php echo $file['stat']; ?></td>
                    <td><?php echo $file['uid']; ?></td>
                    <td><?php echo $file['gid']; ?></td>
                    <td><?php echo $file['lastAccess']; ?></td>
                    <td><?php echo $file['lastModification']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </body>
</html>

