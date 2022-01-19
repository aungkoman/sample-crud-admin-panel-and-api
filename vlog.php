<?php
// Start the session
session_start();
?>

<?php
 /*
    login form ပြမယ်။ session မရှိသေးရန် -> login လုပ်ရန် post key တစ်ခု type = login

    session ရှိရင် json file ကို read ပြီး ဂဏန်းနှစ်ခု ပြထားမယ်။ update လုပ်ရန် key တစ်ခု type = update


 */


 $type = "";
 $live = "";
 if(isset($_POST['type'])){
     $type = $_POST['type'];
     if($type == "login"){
        $username = isset($_POST['username']) ? $_POST['username'] : 'username';
        $password = isset($_POST['password']) ? $_POST['password'] : 'password';

        if($username == "admin" && $password == "password"){
            $_SESSION['login'] = true;
            echo "<h4>Login Success!</h4>";
            $file = "db.json";
            $json = json_decode(file_get_contents($file), true);
            // echo json_encode($json);
            $live = $json["live"];
            $morningset = isset($json['morningset']) ? $json['morningset'] : '0';
            $morningvalue = isset($_jsonPOST['morningvalue']) ? $json['morningvalue'] : '0';
            $morningtwod = isset($json['morningtwod']) ? $json['morningtwod'] : '0';
            $eveningset = isset($json['eveningset']) ? $json['eveningset'] : '0';
            $eveningvalue = isset($json['eveningvalue']) ? $json['eveningvalue'] : '0';
            $eveningtwod = isset($json['eveningtwod']) ? $json['eveningtwod'] : '0';
            $ninemodern = isset($json['ninemodern']) ? $json['ninemodern'] : '0';
            $nineinternet = isset($json['nineinternet']) ? $json['nineinternet'] : '0';
            $twomodern = isset($json['twomodern']) ? $json['twomodern'] : '0';
            $twointernet = isset($json['twointernet']) ? $json['twointernet'] : '0';
        }
        else{
            $_SESSION['login'] = false;
            // echo "<h4>Login Fail!</h4>";
        }
     }
     else if($type == "logout"){
        $_SESSION['login'] = false;
        echo "<h4>Logout Success!</h4>";
     }
     else if($type == "update"){
        $names = isset($_POST['name']) ? $_POST['name'] : [];
        $urls = isset($_POST['url']) ? $_POST['url'] : [];

        $arr = array();
        $index = 0;
        for($i=0; $i < count($names); $i++){
            $arr[] = array(
                "id" => $index,
                "name" => $names[$i],
                "url" => $urls[$i]
            );
            $index++;
        }
        
    
        $file = "db.json";
        file_put_contents($file,json_encode($arr));
        
        echo "<h4>$index vlog is Data Updated!</h4>";
     }
     else if($type == "get"){
        $file = "db.json";
        $json = json_decode(file_get_contents($file), true);
        header('Content-type: application/json'); // return data type is json
        echo json_encode($json);
        die;
     }
 }
 
 

 
 $file = "db.json";
 $json = json_decode(file_get_contents($file), true);
 
 
 $login = true;
 // if(isset($_SESSION['login'])) $login = $_SESSION['login'];

?>

<style>
    *{
        padding: 0px;
        margin: 0px;
        box-sizing: border-box;
    }
    body{
        max-width: 300px;
        border: 1px solid gray;
        margin: auto;
        padding: 8px;
    }
    label, input {
        display: inline-block;
    }
    input{
        margin-bottom: 16px;
    }
    label{
        margin-bottom: 8px;
    }
</style>


<?php if($login == false ) { ?>
    <!-- login form -->
    <form action="" method="post">
        <input type="hidden" name="type" value="login" />

        <label for="username">Username</label>
        <input type="text"  id="username" name="username" required>

        <label for="password">Password</label>
        <input type="password"  id="password" name="password" required>

        <input type="submit" value="Login" />
    </form>

<?php } else {  ?>
    <!-- update form -->
    <form action="" method="post">
        <input type="hidden" name="type" value="update" />

        
        
        <?php 
            foreach ($json as $match) {
                ?>

                    <div>
                                <label for="live">Name</label>
                                <input type="text"   name="name[]" value="<?php echo $match['name']; ?>" required>
                                <br>
                                <label for="live">URL</label>
                                <input type="text"   name="url[]" value="<?php echo $match['url']; ?>" required>
                                <a href="#"><span class="delete">Delete</span></a>
                            </div>
                            <br>
                            <br>

                <?php
            }
                ?>
                
                

                <div id="point"></div>
                
        <input type="submit" value="Update" />
    </form>

    <button id="addMatch">+ Add Vlog</button>

    <!-- logout button -->
    <form action="" method="post">
        <input type="hidden" name="type" value="logout" />

        <!-- <input type="submit" value="Logout" /> -->
    </form>

    <script type="x/template" id="template">
    <div>
                                <label for="live">Name</label>
                                <input type="text"   name="name[]"   required>
                                <br>
                                <label for="live">Url</label>
                                <input type="text"   name="url[]"   required>
                                <a href="#"><span class="delete">Delete</span></a>
                            </div>
                            <br>
                            <br>
    </script>

    <script src="jquery-3.4.1.min.js"></script>
    <script>
        $(document).ready(function(){

            
            function cloneAndAppend(){
                $("#template").clone().insertAfter("#point");
            }

            function addDeleteListener(){
                $(".delete").unbind("click");
                $(".delete").on('click',function(){
                    $(this).parent().parent().remove();
                });
            }

            $('#addMatch').click(function(){
                console.log("#addMatch onClick");
                // $("#car_parent").append($("#car2").clone());
                $("#point").append($("#template").clone().html());
                //console.log($("#template").clone());
                //$("#template").clone().insertAfter("#point");
                addDeleteListener();
            });

            addDeleteListener();
        });
    </script>
<?php } ?>