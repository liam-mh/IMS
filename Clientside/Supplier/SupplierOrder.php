<?php 

//error_reporting(0);

include("../../Serverside/Sessions.php");
include("../../Serverside/Functions.php");

$path = "SupplierLogin.php";
session_start(); 
if (!isset($_SESSION['Username'])) {
    session_unset();
    session_destroy();
    header("Location:".$path);
}
$SupplierName = $_SESSION['Username'];

//Getting logged in supplier category
$db = new SQLite3('/Applications/MAMP/db/IMS.db');
$sql = "SELECT * FROM Supplier WHERE Name = :Name";
$stmt = $db->prepare($sql);
$stmt->bindParam(':Name', $SupplierName, SQLITE3_TEXT); 
$result = $stmt->execute();
$supplier = [];
while($row=$result->fetchArray(SQLITE3_NUM)){$supplier [] = $row;}
$category = $supplier[0][0];

//Getting Order date from Whole_Order for logged in supplier
$db = new SQLite3('/Applications/MAMP/db/IMS.db');
$sql = "SELECT * FROM Whole_Order WHERE Category = :Cat";
$stmt = $db->prepare($sql);
$stmt->bindParam(':Cat', $category, SQLITE3_TEXT); 
$result = $stmt->execute();
$OD = [];
while($row=$result->fetchArray(SQLITE3_NUM)){$OD [] = $row;}
$orderDate = $OD[0][0];



?>

<body>
    <?php require("SupplierNavbar.php");?>
    <div class="container">
  
        <div class="row">

            <div class="col"></div>

            <div class="col-md-5">
                <div class="w1-box">
                    <p style="text-align:center"><?php echo $SupplierName ?> Current Order</p>
                    <br>           
                    <p>ORDER DATE: <?php echo $orderDate ?></p>
                    <table class="styled-table" style="display:block; height:200px; overflow:auto;">
                        <thead>
                            <th>Item</th> 
                            <th>Order Quantity</th>  
                            <th>Total</th> 
                        </thead>
                        <tbody>
                            <?php 
                            $DIO = PlacedDairyIO();
                            for ($i=0; $i<count($DIO); $i++):     
                            ?>
                            <tr> 
                                <td><?php echo $DIO[$i]['Item_Name']?></td>                                                           
                                <td><?php echo $DIO[$i]['Order_Quantity']?></td> 
                                <td>£<?php echo number_format((($DIO[$i]['Total'])/100),2)?></td> 
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                    <br>
                    <p>ORDER TOTAL: £<?php $Dsum=PlacedDairyTP(); echo number_format((($Dsum)/100),2); ?></p>

                    <!-- Accept and Decline buttons -->
                    <form method="post">
                        <div class="row">
                            <div class="col" style="text-align:center"> 
                                <form method="post">  
                                    <input type="submit" value="ACCEPT" class="btn btn-main" name="accept">
                                </form>
                            </div>
                            
                            <div class="col" style="text-align:center"> 
                                <form method="post">                                        
                                    <input type="submit" value="DECLINE" class="btn btn-danger" name="decline">
                                </form>
                            </div>
                        </div> 
                    </form>
                </div>
            </div> 
            
            <div class="col"></div>
        </div> 
    </div>
</body>