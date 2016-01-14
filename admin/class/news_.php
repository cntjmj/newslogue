<?php
    class AdminNews{


        public function ValidateForm($postVar,$action = "Add")
        {
            global $admin_database;
            $errorArray = array();

            $categoryID = $admin_database->cleanXSS($postVar["categoryID"]);
            $newsTitle = $admin_database->cleanXSS($postVar["newsTitle"]);
            $newsDesc = $admin_database->cleanXSS($postVar["newsDesc"]);
            $newsPermalink = str_ireplace(" ","-",$admin_database->cleanXSS($postVar["newsPermalink"]));
            //$newsTag = $admin_database->cleanXSS($postVar["newsTag"]);
            $newsContent = $admin_database->cleanXSS($postVar["newsContent"]);
            $newsQuestion = $admin_database->cleanXSS($postVar["newsQuestion"]);

            $newsStartDate = $admin_database->cleanXSS($postVar["newsStartDate"]);
            $newsStatus = $admin_database->cleanXSS(@$postVar["newsStatus"]);
            $newsBanner = @$_FILES["banner"]["name"];



            if($action == "Add" || $action == "Edit")
            {
                if($categoryID == "")
                    $errorArray[] = "Category Name is required.";

                if($newsTitle == "")
                    $errorArray[] = "News Title is required.";


                if($newsPermalink == "")
                    $errorArray[] = "News Permalink is required.";
                //if($newsTag == "")
                //    $errorArray[] = "News Tag is required.";
                if($newsContent == "")
                    $errorArray[] = "News Content is required.";
                if($newsQuestion == "")
                    $errorArray[] = "News Question is required.";
                if($newsStartDate == "")
                    $errorArray[] = "News Start Date is required.";
                if($newsStatus == "")
                    $errorArray[] = "News Status is required.";

                //if($newsBanner == "" && $action == "Add")
                //    $errorArray[] = "News Banner is required.";

                return $errorArray;
            }
        }
        public function AddDetails($postVar){
            global $connection, $admin_database;

            $categoryID = $admin_database->cleanXSS($postVar["categoryID"]);
            $newsTitle = $admin_database->cleanXSS($postVar["newsTitle"]);
            $newsDesc = $admin_database->cleanXSS($postVar["newsDesc"]);
            $newsPermalink = str_ireplace(" ","-",$admin_database->cleanXSS($postVar["newsPermalink"]));
            //$newsTag = $admin_database->cleanXSS($postVar["newsTag"]);
            // set newsTag as ""
            $newsTag = "";
            $newsContent = $admin_database->cleanXSS($postVar["newsContent"]);
            $newsStartDate = $admin_database->cleanXSS($postVar["newsStartDate"]);
            $newsStatus = $admin_database->cleanXSS(@$postVar["newsStatus"]);
            $newsQuestion = $admin_database->cleanXSS(@$postVar["newsQuestion"]);
            $newsBanner = ""; //@$_FILES["banner"]["name"];
            $newsBannerSource = $admin_database->cleanXSS(@$postVar['newsBannerSource']);
            $newsSource = $admin_database->cleanXSS(@$postVar['newsSource']);
            $nowDateTime = date("Y-m-d H:i:s");
            $adminID = $_SESSION["adminID"];

            if($newsPermalink == "")
                $newsPermalink == str_ireplace(" ","-",$newsTitle);

            if($newsBanner != ""){
            	$newsBanner = $this->ProcessBanner($_FILES["banner"]);
            }
            $newsStartDateArr = explode("-",$newsStartDate);
            $newsStartDate = $newsStartDateArr[2]."-".$newsStartDateArr[1]."-".$newsStartDateArr[0];

            unset($variable);
            $qry = "insert into newsarticle ( categoryID,newsPermalink,newsBanner, newsBannerSource, newsSource,newsTitle,newsDesc,newsTag,newsContent,newsQuestion,newsStartDate,newsStatus,adminID,createdDateTime,updatedDateTime )
                    values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

            $variable[] = array("i", $categoryID);
            $variable[] = array("s", $newsPermalink);
            $variable[] = array("s", $newsBanner);
//           Testing
            $variable[] = array("s", $newsBannerSource);
            $variable[] = array("s", $newsSource);


            $variable[] = array("s", $newsTitle);
            $variable[] = array("s", $newsDesc);

            $variable[] = array("s", $newsTag);
            $variable[] = array("s", $newsContent);
            $variable[] = array("s", $newsQuestion);
            $variable[] = array("s", $newsStartDate);
            $variable[] = array("s", $newsStatus);
            $variable[] = array("s", $adminID);
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("s", $nowDateTime);
            $result = $admin_database->query("insert",$qry,$connection,$variable);







            if($result > 0){
                return $result;
            }
            else
                return false;
        }

        public function SetDetails($postVar,$newsID){
            global $connection, $admin_database;
            $newsID = $admin_database->cleanXSS($newsID);
            $categoryID = $admin_database->cleanXSS($postVar["categoryID"]);
            $newsTitle = $admin_database->cleanXSS($postVar["newsTitle"]);
            $newsDesc = $admin_database->cleanXSS($postVar["newsDesc"]);
            $newsPermalink = str_ireplace(" ","-",$admin_database->cleanXSS($postVar["newsPermalink"]));
            //$newsTag = $admin_database->cleanXSS($postVar["newsTag"]);
            $newsTag = "";
            $newsContent = $admin_database->cleanXSS($postVar["newsContent"]);
            $newsStartDate = $admin_database->cleanXSS($postVar["newsStartDate"]);
            $newsStatus = $admin_database->cleanXSS(@$postVar["newsStatus"]);
            $newsQuestion = $admin_database->cleanXSS(@$postVar["newsQuestion"]);
            $newsBanner = ""; //@$_FILES["banner"]["name"];

//            Testing
            $newsBannerSource = $admin_database->cleanXSS(@$postVar['newsBannerSource']);
            $newsSource = $admin_database->cleanXSS(@$postVar['newsSource']);

            $nowDateTime = date("Y-m-d H:i:s");

            $variable = array();
            $extraSQL = "";

            if($newsPermalink == "")
                $newsPermalink == str_ireplace(" ","-",$newsTitle);



            if($newsBanner != ""){
            	$newsBanner = $this->ProcessBanner($_FILES["banner"]);

                $extraSQL = "newsBanner= ?,";
                $variable[] = array("s", $newsBanner);
            }
            $newsStartDateArr = explode("-",$newsStartDate);
            $newsStartDate = $newsStartDateArr[2]."-".$newsStartDateArr[1]."-".$newsStartDateArr[0];

            // Dont need to update adminID for this news
            $qry = "update newsarticle set
                    ".$extraSQL."
                    categoryID = ?,
                    newsPermalink= ?,

                    newsBannerSource= ?,
                    newsSource= ?,

                    newsTitle= ?,
                    newsDesc = ?,
                    newsTag= ?,
                    newsContent= ?,
                    newsQuestion= ?,
                    newsStartDate= ?,
                    newsStatus= ?,
                    updatedDateTime = ?
                    where newsID = ?";
            $variable[] = array("i", $categoryID);
            $variable[] = array("s", $newsPermalink);

            //           Testing
            $variable[] = array("s", $newsBannerSource);
            $variable[] = array("s", $newsSource);


            $variable[] = array("s", $newsTitle);
            $variable[] = array("s", $newsDesc);

            $variable[] = array("s", $newsTag);
            $variable[] = array("s", $newsContent);
            $variable[] = array("s", $newsQuestion);
            $variable[] = array("s", $newsStartDate);
            $variable[] = array("s", $newsStatus);
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("i", $newsID);

            $result = $admin_database->query("update",$qry,$connection,$variable);

            if($result > 0){
                return true;
            }
            else
                return false;
        }


        public function DisplayAllDetails($pageNo,$itemPerPage= 1,$filter){
            global $connection,$admin_database;


			$variable = array();
			$qry = "select * from `newsarticle` ";
			$result = $admin_database->query("select",$qry,$connection,$variable);
			$totalItem = count($result);
			$lastpage = ceil($totalItem/$itemPerPage);

			$returnArray["TotalResult"] = $totalItem;

			$statement = "select newsarticle.*, administrator.username as adminName from `newsarticle`
					inner join `administrator` on newsarticle.adminID=administrator.adminID 
					order by createdDateTime desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
			$result2 = $admin_database->query("select",$statement,$connection,$variable);


            if($result2 > 0)
            {
                for($xx=0;$xx<count($result2);$xx++)
                {
                    foreach($result2[$xx] as $id => $value)
                    {
                        if(!is_numeric($id))
                        {
                            $returnArray["List"][$xx][$id] = $admin_database->cleanData($value);
                        }
                    }
                }

                return $returnArray;
            }
            else
                return false;

        }




         public function GetDetails($newsID){
            global $connection,$admin_database;
            $newsID = $admin_database->cleanXSS($newsID,"int");


            unset($variable);
            $qry = "select * from newsarticle where newsID = ?";
            $variable[] = array("i", $newsID);
            $result = $admin_database->query("select",$qry,$connection,$variable);

            if(is_array($result) && count($result) > 0)
            {
                foreach($result[0] as $id => $value)
                {
                    if(!is_numeric($id))
                    {
                        $returnArray[$id] = $admin_database->cleanData($value);
                    }
                }
                return $returnArray;
            }
            else
                return false;
        }




        public function DeleteDetails($newsID){
            global $connection,$admin_database;
            $newsID = $admin_database->cleanXSS($newsID,"int");


            unset($variable);
            $qry = "delete from `newsarticle`  where newsID = ?";
            $variable[] = array("i", $newsID);
            $result = $admin_database->query("delete",$qry,$connection,$variable);



            if($result > 0){
                return true;
            }
            else
                return false;
        }



        public function CropImageIntoFolder($target_folder, $folder_postfix, $folder_upload, $banner){
            global $admin_image;

            list($width,  $height) = getimagesize($folder_upload);

            $popupWidth = $width;
            $popupHeight = $height;
            $profileWidth = $width;
            $profileHeight = $height;
            $thumbnailWidth = $width;
            $thumbnailHeight = $height;

            $resizeArray["thumbnail"] = array(
                "width" => $width,
                "height" => $height,
                // "resizeWidth" => 780,
                // "resizeHeight" => 390,
                "resizeWidth" => 500,
                "resizeHeight" => 250,                
                "source" => $folder_upload,
                "quality" => 10,
                "square" => false,
                "destination" => $target_folder."thumbnail/".$folder_postfix
            );

            foreach($resizeArray as $id => $value)
            {
                //while($value["width"] > $value["resizeWidth"] || $value["height"] > $value["resizeHeight"]){

                if($value["width"] > $value["resizeWidth"] || $value["width"] < $value["resizeWidth"]){
                    $ratio = $value["resizeWidth"]/$value["width"];
                    $value["height"] = $value["height"] * $ratio;
                    $value["width"] = $value["resizeWidth"];
                }


                $admin_image->settings($value["source"],$value["width"],$value["height"],$value["quality"],$value["square"],"",$value["destination"]);
                $rst = $admin_image->resize();
            }

            // for SNS link image ----------------------------------

            // echo"<script>alert('$banner')</script>";
            $snsimag_destination= "../uploads/banner/forsns/" . $folder_postfix . $banner;
            $banner_location = "../uploads/banner/thumbnail/" . $folder_postfix . $banner;
            $im3 = new ImageManipulator($banner_location);
            $im3->resample(250, 125);
            $im3->save($snsimag_destination);

        }



        public function AddThoughts($postVar){
            global $connection, $admin_database;

            $thoughts = $admin_database->cleanXSS($postVar["thoughts"]);
            $thoughtsOrder = $admin_database->cleanXSS($postVar["thoughtsOrder"]);
            $thoughtsStatus = $admin_database->cleanXSS($postVar["thoughtsStatus"]);
            $newsID = $admin_database->cleanXSS($postVar["newsID"]);

            $nowDateTime = date("Y-m-d H:i:s");

            unset($variable);
            $qry = "insert into newsarticle_thoughts ( newsID,thoughts,thoughtsOrder,thoughtsStatus,createdDateTime,updatedDateTime)
                    values (?,?,?,?,?,?)";
            $variable[] = array("i", $newsID);
            $variable[] = array("s", $thoughts);
            $variable[] = array("s", $thoughtsOrder);
            $variable[] = array("s", $thoughtsStatus);
            $variable[] = array("s", $nowDateTime);
            $variable[] = array("s", $nowDateTime);
            $result = $admin_database->query("insert",$qry,$connection,$variable);

            if($result > 0){
                return $result;
            }
            else
                return false;
        }

        public function SetThoughts($postVar,$thoughtsID){
            global $connection, $admin_database;

            $thoughts = $admin_database->cleanXSS($postVar["thoughts"]);
            $thoughtsOrder = $admin_database->cleanXSS($postVar["thoughtsOrder"]);
            $thoughtsStatus = $admin_database->cleanXSS($postVar["thoughtsStatus"]);
            $thoughtsID = $admin_database->cleanXSS($thoughtsID);

            $nowDateTime = date("Y-m-d H:i:s");

            unset($variable);
            $qry = "update newsarticle_thoughts set thoughts = ?, thoughtsOrder = ?, thoughtsStatus = ? where thoughtsID = ?";
            $variable[] = array("s", $thoughts);
            $variable[] = array("s", $thoughtsOrder);
            $variable[] = array("s", $thoughtsStatus);
            $variable[] = array("i", $thoughtsID);
            $result = $admin_database->query("update",$qry,$connection,$variable);

            if($result > 0){
                return true;
            }
            else
                return false;
        }
        public function DeleteThoughts($thoughtsID){
            global $connection, $admin_database;

            $thoughtsID = $admin_database->cleanXSS($thoughtsID);

            $nowDateTime = date("Y-m-d H:i:s");

            unset($variable);
            $qry = "delete from newsarticle_thoughts where thoughtsID = ?";
            $variable[] = array("i", $thoughtsID);
            $result = $admin_database->query("delete",$qry,$connection,$variable);

            if($result > 0){
                return true;
            }
            else
                return false;
        }


        public function GetThoughts($thoughtsID){
            global $connection, $admin_database;

            $thoughtsID = $admin_database->cleanXSS($thoughtsID);


            $nowDateTime = date("Y-m-d H:i:s");

            unset($variable);
            $qry = "select * from newsarticle_thoughts where thoughtsID = ?";

            $variable[] = array("i", $thoughtsID);
            $result = $admin_database->query("select",$qry,$connection,$variable);

            if(is_array($result) && count($result) > 0)
            {
                foreach($result[0] as $id => $value)
                {
                    if(!is_numeric($id))
                    {
                        $returnArray[$id] = $admin_database->cleanData($value);
                    }
                }
                return $returnArray;
            }
            else
                return false;
        }
        
        public function ProcessBanner($file_banner) {
        	$newsBanner = @$file_banner["name"];
        	$banner_str_replaced = "";

        	if ($newsBanner != "") {
	        	$explodedMainImageArray = explode('.', $newsBanner);
	        	$ext = strtolower(end($explodedMainImageArray));
	        	if($ext != "jpg" && $ext != "jpeg" && $ext != "png" && $ext != "gif"){
	        		$_SESSION["error_notification"] = "<strong>News Banner</strong> field must be in jpg, jpeg, gif or png.<br />";
	        		return false;
	        	}
	        	
	        	$target_folder = "../uploads/banner/";
	        	
	        	$banner_path_postfix = ($file_banner["size"] / 10 % 10) ."/";
	        	
	        	if (!file_exists($target_folder."original/".$banner_path_postfix)) {
	        		mkdir($target_folder."original/".$banner_path_postfix);
	        		mkdir($target_folder."thumbnail/".$banner_path_postfix);
	        		mkdir($target_folder."forsns/".$banner_path_postfix);
	        	}
	        	
	        	$banner_path_postfix = $banner_path_postfix. ($file_banner["size"] % 10) ."/";
	        	if (!file_exists($target_folder."original/".$banner_path_postfix)) {
	        		mkdir($target_folder."original/".$banner_path_postfix);
	        		mkdir($target_folder."thumbnail/".$banner_path_postfix);
	        		mkdir($target_folder."forsns/".$banner_path_postfix);
	        	}
	        	
	        	// Replace spaces with hyphens in file name
	        	$banner_str_replaced = str_replace(" ", "-",$newsBanner);
	        	
	        	$full_original_path = $target_folder."original/".$banner_path_postfix.$banner_str_replaced;
	        	//$thumbnail_folder = $target_folder."thumbnail/";
	        	
	        	if(file_exists($full_original_path) && !is_dir($full_original_path)){
	        		$files = explode('.', $banner_str_replaced);
	        		$banner_str_replaced = $files[0].uniqid().".".strtolower($files[1]);
	        		$full_original_path = $target_folder."original/".$banner_path_postfix.$banner_str_replaced;
	        	}
	        	
	        	move_uploaded_file(@$file_banner["tmp_name"],$full_original_path);
	        	$this->CropImageIntoFolder($target_folder, $banner_path_postfix, $full_original_path, $banner_str_replaced);
	        	//$this->AddNewThumbnail($result,@$_FILES["banner"]["tmp_name"],$folder_upload,$newsBanner,true);
	        	
	        	$banner_str_replaced = $banner_path_postfix . $banner_str_replaced;
        	}

        	return $banner_str_replaced;
        }

        public function DisplayAllThoughts($pageNo,$itemPerPage= 1,$newsID){
            global $connection,$admin_database;


            $variable = array();
            $qry = "select * from `newsarticle_thoughts` where newsID = ? and thoughtsStatus = 'active' ";
            $variable[] = array("i",$newsID);
            $result = $admin_database->query("select",$qry,$connection,$variable);
            $totalItem = count($result);
            $lastpage = ceil($totalItem/$itemPerPage);

            $returnArray["TotalResult"] = $totalItem;

//            $statement = "select * from `newsarticle_thoughts` where newsID = ? and thoughtsStatus = 'active' order by createdDateTime desc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
            $statement = "select * from `newsarticle_thoughts` where newsID = ? order by thoughtsOrder asc limit ".($pageNo-1)*$itemPerPage.",".$itemPerPage;
            $result2 = $admin_database->query("select",$statement,$connection,$variable);


            if($result2 > 0)
            {
                for($xx=0;$xx<count($result2);$xx++)
                {
                    foreach($result2[$xx] as $id => $value)
                    {
                        if(!is_numeric($id))
                        {
                            $returnArray["List"][$xx][$id] = $admin_database->cleanData($value);
                        }
                    }
                }

                return $returnArray;
            }
            else
                return false;

        }

    }

?>
