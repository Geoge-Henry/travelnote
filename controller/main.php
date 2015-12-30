<?php
class main extends spController
{
	function index(){ // 这里是首页
        $travelnote = spClass("travelnote_t");
        $guestbook = spClass("guestbook");
        $jsonarray = array();
        $path = "saestor://travelnote/1@m.scnu.edu.cn/note/合同额度/2015年03月28日/img1_s.jpg";
        $filename = "saestor://travelnote/1@m.scnu.edu.cn/note/合同额度/2015年03月31日/img1.jpg";
        $filename1 = "saestor://travelnote/1@m.scnu.edu.cn/note/合同额度/2015年03月31日/img1_s.jpg";
        unlink($filename);
        unlink($filename1);
        rmdir("http://travelsky-travelnote.stor.sinaapp.com/1@m.scnu.edu.cn/note/让他广告/2015年03月31日");
        
        //$filename_1 = "saestor://travelnote/晓春";
        //rename($filename,$filename_1);
        //unlink($filename);
        //$file = file_get_contents($filename);
        //file_put_contents("saestor://travelnote/1@m.scnu.edu.cn/note/晓春/yoona.jpg",$file);
        if(!empty($filename))
            echo "aaaa";
        else
            echo "aasd";
        
        $sql="select id,title,date,location,author,img_url_s from travelnote_t order by id desc limit 10";
         if( $result = $travelnote->findSql($sql) ){ // 用findAll将全部的留言查出来
        foreach($result as  $value){
            $condition = array('email'=>$value['author']);
            $result1 = $guestbook->find($condition);
            $value['author'] = $result1['user'];
            $value['title'] = urlencode($value['title']); 
            $value['date'] = urlencode($value['date']);
            $value['location'] = urlencode($value['location']);
            //echo urldecode("%E5%B9%BF%E5%B7%9E");
            $value['author'] = urlencode($value['author']);
            $value['img_head'] = "http://travelsky-travelnote.stor.sinaapp.com/".$result1['email']."/head/img_head.jpg";
            $value['img_url_s'] = urlencode($value['img_url_s']);
            $json = array(
                'id'=>$value['id'],
                'title'=>$value['title'],
                'date'=>$value['date'],
                'location'=>$value['location'],
                'author'=>$value['author'],
                'img_url_s'=>$value['img_url_s'],
                'img_head'=>$value['img_head'],
            );
            array_push($jsonarray,$json);
        	} 
            $jsonarray1 = array('jsonarray'=>$jsonarray);
            echo urldecode(json_encode($jsonarray1));
        }
	}
    function login(){
        $guestbook = spClass("guestbook");
        $email = $this->spArgs("email");
        $password = $this->spArgs("password");
        $condition = array(
            "email" => $email,
        );
        $result = $guestbook->find($condition);
        if($result!=null)
        {
            if($result['password']==$password)
            {
                if(empty($result['head']))				//是否有头像
                {
                    $info = array(
                        "flag"=>"success",
                    	"user"=>urlencode($result['user']),
                        "head"=>"http://travelsky-travelnote.stor.sinaapp.com/default.png",
                	);
                    echo urldecode(json_encode($info));
                }
                else
                {
                    $info = array(
                        "flag"=>"success",
                        "user"=>urlencode($result['user']),
                        "head"=>"http://travelsky-travelnote.stor.sinaapp.com/".$result['email'].'/'.'head/img_head.jpg',
                    );
                    echo urldecode(json_encode($info));
                }
            }
        }
        else
            {
                $info = array(
                    "flag" => "failed",
                	"user"=>null,
                    "head"=>null,
                );
                echo json_encode($info);
            }
    }
    function register(){
        $guestbook = spClass("guestbook");
        $email = $this->spArgs("email");
        $password = $this->spArgs("password");
        $user = $this->spArgs("user");
        $condition = array("email"=>$email);
        $result = $guestbook->findAll($condition);
        if($result == null){
        	$newrow = array(
            	"email" => $email,
            	"password"=>$password,
				"user"=>$user, 
                       	);
        	$guestbook->create($newrow);
        	echo "success";
        }
        else{
            echo "used";
        }
    }
    
    function return_list_new(){									//刷新列表
       $travelnote = spClass("travelnote_t");
        $guestbook = spClass("guestbook");
        $jsonarray = array();
        $sql="select id,title,date,location,author,img_url_s from travelnote_t order by id desc limit 10";
        if( $result = $travelnote->findSql($sql) ){ 
        foreach($result as  $value){
            $condition = array('email'=>$value['author']);
            $result1 = $guestbook->find($condition);
            $value['author'] = $result1['user'];
            $value['title'] = urlencode($value['title']); 
            $value['date'] = urlencode($value['date']);
            $value['location'] = urlencode($value['location']);
            //echo urldecode("%E5%B9%BF%E5%B7%9E");
            $value['author'] = urlencode($value['author']);
            $value['img_head'] = urlencode("http://travelsky-travelnote.stor.sinaapp.com/".$result1['email']."/head/img_head.jpg");
            $value['img_url_s'] = urlencode($value['img_url_s']);
            $result1['user'] = urlencode($result1['email']);
            $json = array(
                'id'=>$value['id'],
                'email'=>$result1['email'],
                'title'=>$value['title'],
                'date'=>$value['date'],
                'location'=>$value['location'],
                'author'=>$value['author'],
                'img_url_s'=>$value['img_url_s'],
                'img_head'=>$value['img_head'],
            );
            array_push($jsonarray,$json);
        	} 
            $jsonarray1 = array('jsonarray'=>$jsonarray);
            echo urldecode(json_encode($jsonarray1));
        }
    }
    
    function return_list_old(){
       $travelnote = spClass("travelnote_t");
        $guestbook = spClass("guestbook");
        $id = $this->spArgs("id");
        $jsonarray = array();
        $sql="select id,title,date,location,author,img_url_s from travelnote_t where id<".$id." order by id desc limit 10";
        if( $result = $travelnote->findSql($sql) ){ 
        foreach($result as  $value){
            $condition = array('email'=>$value['author']);
            $result1 = $guestbook->find($condition);
            $value['author'] = $result1['user'];
            $value['title'] = urlencode($value['title']); 
            $value['date'] = urlencode($value['date']);
            $value['location'] = urlencode($value['location']);
            //echo urldecode("%E5%B9%BF%E5%B7%9E");
            $value['author'] = urlencode($value['author']);
            $value['img_head'] = urlencode("http://travelsky-travelnote.stor.sinaapp.com/".$result1['email']."/head/img_head.jpg");
            $value['img_url_s'] = urlencode($value['img_url_s']);
            $result1['user'] = urlencode($result1['email']);
            $json = array(
                'id'=>$value['id'],
                'email'=>$result1['email'],
                'title'=>$value['title'],
                'date'=>$value['date'],
                'location'=>$value['location'],
                'author'=>$value['author'],
                'img_url_s'=>$value['img_url_s'],
                'img_head'=>$value['img_head'],
            );
            array_push($jsonarray,$json);
        	} 
            $jsonarray1 = array('jsonarray'=>$jsonarray);
            echo urldecode(json_encode($jsonarray1));
        }
    }
    
	function show(){ // 这里是查看留言内容
		$id = $this->spArgs("id"); // 用spArgs接收spUrl传过来的ID
		$guestbook = spClass("guestbook");  // 还是用spClass
		$condition = array('id'=>$id); // 制造查找条件，这里是使用ID来查找属于ID的那条留言记录
		$result = $guestbook->find($condition);  // 这次是用find来查找，我们把$condition（条件）放了进去
		// 下面输出了该条留言内容
		echo "<p>留言标题：{$result['title']}</p>";
		echo "<p>留言者：{$result['name']}</p>";
		echo "<p>留言内容：{$result['contents']}</p>";
	}
    function set_head(){
        $guestbook = spClass("guestbook");
        $email = $this->spArgs("email");
        $condition = array("email"=>$email);
        $newrow = array("head"=>"Y");
        $image_head_64 = $this->spArgs("image_head");
        $image_head_bin = base64_decode($image_head_64);				//解码
        $path = "saestor://travelnote/".$email."/head/img_head.jpg";
        file_put_contents($path,$image_head_bin);
        $guestbook->update($condition,$newrow);
        echo "success";
    }
    
    function set_name(){
        $guestbook = spClass("guestbook");
        $email = $this->spArgs("email");
        $user = $this->spArgs("user");
        $condition = array(
            "email"=>$email,
        );
        $newrow = array("user"=>$user);
        $guestbook->update($condition,$newrow);
        echo "success";
    }
    
    function set_password(){
        $guestbook = spClass("guestbook");
        $email = $this->spArgs("email");
        $password = $this->spArgs("password");
        $condition = array(
            "email"=>$email,
        );
        $newrow = array("password"=>$password);
        $guestbook->update($condition,$newrow);
        echo "success";
    }
    
	function write(){ // 这里是留言
		$guestbook = spClass("guestbook");
		$newrow = array( // 这里制作新增记录的值
			'name' => $this->spArgs('name'), 
			'title' => $this->spArgs('title'), // 从spArgs获取到表单提交上来的title
			'contents' => $this->spArgs('contents'),
		);
		$guestbook->create($newrow); 
		echo "留言成功，<a href=/index.php>返回</a>";
	}
    
	function test_update(){ // 测试update用页面
		$guestbook = spClass("guestbook");
		$conditions = array("id"=>2); // 查找id是2的记录
		$newrow = array(
			'name' => '喜羊羊',  // 然后将这条记录的name改成“喜羊羊”
		);
		$guestbook->update($conditions, $newrow); // 更新记录
		echo "已修改id为2的记录！";
	}
    
	function test_delete(){ // 测试delete用页面
		$guestbook = spClass("guestbook");
		$conditions = array('name' => '喜羊羊');
		$guestbook->delete($conditions); // 删除记录
		echo "已删除名称是喜羊羊的记录！";
	}
    
    function searching(){
		$travelnote_t = spClass('travelnote_t');
        $guestbook = spClass("guestbook");
        $jsonarray = array();
        $location = $this->spArgs('location');
        $sign = $this->spArgs('sign');
        //$id = $this->spArgs('id');
        //$sql1 = "select id from travelnote_t order by id desc limit 1";
        if(empty($sign))
            $sql="select id,title,date,location,author,img_url_s from travelnote_t where location like '%".$location."%' order by id desc limit 10";
        elseif(empty($location))
            $sql="select id,title,date,location,author,img_url_s from travelnote_t where sign like '%".$sign."%' order by id desc limit 10";      	
        else
            $sql="select id,title,date,location,author,img_url_s from travelnote_t where location like '%".$location."%' and sign like '%".$sign."%' order by id desc limit 10";
        
        //$result_1 = $travelnote_t->findSql($sql1);
        
        //foreach($result_1 as $value)
        //{    
        //if($value['id'] == $id)
        //$result = false;
        //else
        //$result = true;
        //}
        //if($result)
        //{
        	if($result = $travelnote_t->findSql($sql))
        	{
            	foreach($result as $value)
            	{
                	$condition = array('email'=>$value['author']);
            		$result1 = $guestbook->find($condition);
            		$value['author'] = $result1['user'];
            		$value['title'] = urlencode($value['title']); 
            		$value['date'] = urlencode($value['date']);
            		$value['location'] = urlencode($value['location']);
            		//echo urldecode("%E5%B9%BF%E5%B7%9E");
            		$value['author'] = urlencode($value['author']);
            		$value['img_head'] =urlencode("http://travelsky-travelnote.stor.sinaapp.com/".$result1['email']."/head/img_head.jpg");
            		$value['img_url_s'] = urlencode($value['img_url_s']);
            		$json = array(
                		'id'=>$value['id'],
                		'title'=>$value['title'],
                    	'email'=>$result1['email'],
                		'date'=>$value['date'],
                		'location'=>$value['location'],
                		'author'=>$value['author'],
                		'img_url_s'=>$value['img_url_s'],
                		'img_head'=>$value['img_head'],
            		);
            	array_push($jsonarray,$json);
        		} 
            	$jsonarray1 = array('jsonarray'=>$jsonarray);
            	echo urldecode(json_encode($jsonarray1));
        	}
        //}
    }
    
    function searching_down(){
        $travelnote_t = spClass('travelnote_t');
        $guestbook = spClass("guestbook");
        $jsonarray = array();
        $id = $this->spArgs("id");
        $location = $this->spArgs("location");
        $sign = $this->spArgs("sign");
        //$sql1 = "select id from travelnote_t order by id asc limit 1";
        
        if(empty($sign))
        	$sql = "select id,title,date,location,author,img_url_s from travelnote_t where location like '%".$location."%' and id<".$id." order by id desc limit 10";
        elseif(empty($location))
            $sql = "select id,title,date,location,author,img_url_s from travelnote_t where sign like '%".$sign."%' and id<".$id." order by id desc limit 10";
        else 
            $sql = "select id,title,date,location,author,img_url_s from travelnote_t where location like '%".$location."%' and sign like '%".$sign."%' and id<".$id." order by id desc limit 10";
        
        //$result_1 = $travelnote_t->findSql($sql1);
        
        //foreach($result_1 as $value)
            //{    
        //if($value['id'] == $id)
        //$result = false;
        //else
        //$result = true;
            //}
        //if($result)
        //{
        	if($result = $travelnote_t->findSql($sql))
        	{
            	foreach($result as $value)
            	{
                	$condition = array('email'=>$value['author']);
            		$result1 = $guestbook->find($condition);
            		$value['author'] = $result1['user'];
            		$value['title'] = urlencode($value['title']); 
            		$value['date'] = urlencode($value['date']);
            		$value['location'] = urlencode($value['location']);
            		//echo urldecode("%E5%B9%BF%E5%B7%9E");
            		$value['author'] = urlencode($value['author']);
            		$value['img_head'] =urlencode("http://travelsky-travelnote.stor.sinaapp.com/".$result1['email']."/head/img_head.jpg");
            		$value['img_url_s'] = urlencode($value['img_url_s']);
            		$json = array(
                		'id'=>$value['id'],
                		'title'=>$value['title'],
                    	'email'=>$result1['email'],
                		'date'=>$value['date'],
                		'location'=>$value['location'],
                		'author'=>$value['author'],
                		'img_url_s'=>$value['img_url_s'],
                		'img_head'=>$value['img_head'],
            		);
            	array_push($jsonarray,$json);
        		} 
            	$jsonarray1 = array('jsonarray'=>$jsonarray);
            	echo urldecode(json_encode($jsonarray1));
        	}
        //}
        // else 
        //echo "no more";
    }
    
    function imgStore(){   //文件上传，按钮
        	echo $_FILES["file"]["type"];
            echo $result;
  			if ($_FILES["file"]["error"] > 0)
    		{
    			echo "Return Code: " . $_FILES["file"]["error"] . "<br/>";
    		}
 		    else
    		{
    			echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    			echo "Type: " . $_FILES["file"]["type"] . "<br />";
    			echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    			echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

    			if (file_exists("sae" . $_FILES["file"]["name"]))
      			{
      				echo $_FILES["file"]["name"] . " already exists. ";
      			}
    			else
      			{
        			move_uploaded_file($_FILES["file"]["tmp_name"], "saestor://travelnote/" . $_FILES["file"]["name"]);
        			echo "Stored in: " . "saestor://travelnote/" . $_FILES["file"]["name"];
      			}
    		}
        
    }
    
    function imgStore_base64(){  //图片接收
        $date = $this->spArgs("date");
        $email = $this->spArgs("email");
        $note_name = $this->spArgs("note_name");
        $path1 = "saestor://travelnote/".$email.'/'.$sign.'/'.$note_name.'/'.$date.'/';
        $image_641 = $this->spArgs("image1");
        $image_bin1 = base64_decode($image_641);
        $image_name2 = $this->spArgs("image_name2");
        $path2 = "saestor://travelnote/".$image_name2;
        $image_642 = $this->spArgs("image2");
        $image_bin2 = base64_decode($image_642);
        $image_name3 = $this->spArgs("image_name3");
        $path3 = "saestor://travelnote/".$image_name3;
        $image_643 = $this->spArgs("image3");
        $image_bin3 = base64_decode($image_643);
        file_put_contents($path1, $image_bin1);
        file_put_contents($path2, $image_bin2);
        file_put_contents($path3, $image_bin3);
        echo "success";
    }
    
    function travelnote_store(){
        $travelnote = spClass("travelnote");
        $travelnote_t = spClass("travelnote_t");
        $content = $this->spArgs("content"); 
        $date = $this->spArgs("date");
        $email = $this->spArgs("email");
        $title = $this->spArgs("title");
        $sign1 = $this->spArgs("sign1");
        $location = $this->spArgs("location");
        $image_641 = $this->spArgs("image1");
        $image_641_s = $this->spArgs("image1_s");
        $image_642 = $this->spArgs("image2");
        $image_642_s = $this->spArgs("image2_s");
        $image_643 = $this->spArgs("image3");
        $image_643_s = $this->spArgs("image3_s");
        $condition = array(
            'author'=>$email,
            'title'=>$title,
        );
        echo $email;
        
   		if(empty($image_641))
        {
   
            $path1 = null;
            $path1_1 = null;
            echo "failed";
        }
        else
        {
            $image_bin1 = base64_decode($image_641);
            $path1 = "saestor://travelnote/".$email."/note/".'/'.$title.'/'.$date.'/'."img1.jpg";
            $path1_1 = "http://travelsky-travelnote.stor.sinaapp.com/".$email."/note/".$title."/".$date."/img1.jpg";
            file_put_contents($path1, $image_bin1);						//把一段字符串写入文件中
            echo "success";
        }
        
        if(empty($image_641_s))
        {
   
            $path1_s = null;
            $path1_1_s = null;
            echo "failed";
        }
        else
        {
            $image_bin1_s = base64_decode($image_641_s);
            $path1_s = "saestor://travelnote/".$email."/note/".'/'.$title.'/'.$date.'/'."img1_s.jpg";
            $path1_1_s = "http://travelsky-travelnote.stor.sinaapp.com/".$email."/note/".$title."/".$date."/img1_s.jpg";
            file_put_contents($path1_s, $image_bin1_s);
            echo "success";
        }
        
        if(empty($image_642))
        {
            $path2 = null;
            $path2_1 = null;
            echo "failed";
        }
        else
        {
			 $image_bin2 = base64_decode($image_642);
             $path2 = "saestor://travelnote/".$email."/note/".'/'.$title.'/'.$date.'/'."img2.jpg";
             $path2_1 = "http://travelsky-travelnote.stor.sinaapp.com/".$email."/note/".$title."/".$date."/img2.jpg";
             file_put_contents($path2, $image_bin2);
             echo "success";
        }
        
        if(empty($image_642_s))
        {
   
            $path2_s = null;
            $path2_1_s = null;
            echo "failed";
        }
        else
        {
            $image_bin2_s = base64_decode($image_642_s);
            $path2_s = "saestor://travelnote/".$email."/note/".'/'.$title.'/'.$date.'/'."img2_s.jpg";
            $path2_1_s = "http://travelsky-travelnote.stor.sinaapp.com/".$email."/note/".$title."/".$date."/img2_s.jpg";
            file_put_contents($path2_s, $image_bin2_s);
            echo "success";
        }
        
        if(empty($image_643))
        {
            $path3 = null;
            $path3_1 = null;
			echo "failed";
        }
        else
        {
        	$image_bin3 = base64_decode($image_643);
            $path3 = "saestor://travelnote/".$email."/note/".'/'.$title.'/'.$date.'/'."img3.jpg";
            $path3_1 = "http://travelsky-travelnote.stor.sinaapp.com/".$email."/note/".$title."/".$date."/img3.jpg";
            file_put_contents($path3, $image_bin3);
            echo "success";
        }
        
        if(empty($image_643_s))
        {
   
            $path3_s = null;
            $path3_1_s = null;
            echo "failed";
        }
        else
        {
            $image_bin3_s = base64_decode($image_643_s);
            $path3_s = "saestor://travelnote/".$email."/note/".'/'.$title.'/'.$date.'/'."img3_s.jpg";
            $path3_1_s = "http://travelsky-travelnote.stor.sinaapp.com/".$email."/note/".$title."/".$date."/img3_s.jpg";
            file_put_contents($path3_s, $image_bin3_s);
            echo "success";
        }
         
        $newrow = array(
            "title" => $title,
        	"date" => $date,
            "location" => $location,
            "sign1" => $sign1,
            "author" => $email,
            "good" => 0,
            "content" => $content,
            "img_url1" => $path1_1,
            "img_url2" => $path2_1,
            "img_url3" => $path3_1,
            "img_url1_s"=>$path1_1_s,
            "img_url2_s"=>$path2_1_s,
            "img_url3_s"=>$path3_1_s,
        );
        $newrow_1 = array(
            "title" => $title,
            "date" => $date,
            "location" => $location,
            "sign" => $sign1,
            "author"=>$email,
            "img_url_s"=>$path1_1_s,
            "zang"=>0,
        );
        $travelnote->create($newrow);
        $result = $travelnote_t->find($condition);
        if($result!=null)
        {
            $travelnote_t->delete($condition);
        	$travelnote_t->create($newrow_1);
        }
        else
        {
            $travelnote_t->create($newrow_1);
        }       
    }
    
    function continue_note(){
        $travelnote = spClass("travelnote");
        $email = $this->spArgs("email");
        $sql = "SELECT title FROM `travelnote` WHERE author='".$email."'order by id desc limit 1";
        //echo $sql;
        $result = $travelnote->findSql($sql);
        foreach($result as $value)
        echo $value['title'];
    }
    
    function title_revise(){
        $travelnote = spClass('travelnote');
        $travelnote_t = spClass('travelnote_t');
        $email = $this->spArgs('email');
        $title = $this->spArgs('title');
        $new_title = $this->spArgs('new_title');
        $condition = array(
            'author'=>$email,
            'title'=>$title,
        );
		$result = $travelnote->findAll($condition);
        foreach($result as $value)
        {
            if(!empty($value['img_url1']))
            {
                $path = "saestor://travelnote/".$value['author'].'/note/'.$new_title.'/'.$value['date'].'/'.'img1.jpg';
                $path_s = "saestor://travelnote/".$value['author'].'/note/'.$new_title.'/'.$value['date'].'/'.'img1_s.jpg';
                $path_r = "http://travelsky-travelnote.stor.sinaapp.com/".$value['author'].'/note/'.$new_title.'/'.$value['date'].'/'.'img1.jpg';
                $path_r_s = "http://travelsky-travelnote.stor.sinaapp.com/".$value['author'].'/note/'.$new_title.'/'.$value['date'].'/'.'img1_s.jpg';
                
                $file = file_get_contents($value['img_url1']);
                $file_s = file_get_contents($value['img_url1_s']);
                file_put_contents($path,$file);
                file_put_contents($path_s,$file_s);  
                
                unlink("saestor://travelnote/".$value['author'].'/note/'.$title.'/'.$value['date'].'/img1.jpg');
 				unlink("saestor://travelnote/".$value['author'].'/note/'.$title.'/'.$value['date'].'/img1_s.jpg'); 
                rmdir("http://travelsky-travelnote.stor.sinaapp.com".$value['author']."/note/".$title.'/'.$valuep['date']);
                
                $condition = array(
                    'author' => $email,
                    'title' => $title,
                    'date' => $value['date'],
                );
                $condition_1 = array(
                    'author'=>$email,
                    'title'=>$title,
                );
                $newrow = array(
                    'title'=>$new_title,
                    'img_url1'=> $path_r,
                    'img_url1_s'=>$path_r_s,
                );
                $newrow_1 = array(
                    'title'=>$new_title,
                    'img_url_s'=>$path_r_s,
                );
                
                $travelnote->update($condition,$newrow);
                $travelnote_t->update($condition_1,$newrow_1);
            }
            if(!empty($value['img_url2']))
            {
                $path = "saestor://travelnote/".$value['author'].'/note/'.$new_title.'/'.$value['date'].'/'.'img2.jpg';
                $path_s = "saestor://travelnote/".$value['author'].'/note/'.$new_title.'/'.$value['date'].'/'.'img2_s.jpg';
                $path_r_1 = "http://travelsky-travelnote.stor.sinaapp.com/".$value['author'].'/note/'.$new_title.'/'.$value['date'].'/'.'img2.jpg';
                $path_r_s_1 = "http://travelsky-travelnote.stor.sinaapp.com/".$value['author'].'/note/'.$new_title.'/'.$value['date'].'/'.'img2_s.jpg';
                
                $file = file_get_contents($value['img_url2']);
                $file_s = file_get_contents($value['img_url2_s']);
                file_put_contents($path,$file);
                file_put_contents($path_s,$file_s);
                
                unlink("saestor://travelnote/".$value['author'].'/note/'.$title.'/'.$value['date'].'/img2.jpg');
 				unlink("saestor://travelnote/".$value['author'].'/note/'.$title.'/'.$value['date'].'/img2_s.jpg'); 
                rmdir("http://travelsky-travelnote.stor.sinaapp.com".$value['author']."/note/".$title.'/'.$valuep['date']);

                $condition = array(
                    'author' => $email,
                    'title' => $title,
                    'date' => $value['date'],
                );
                $newrow = array(
                    'title'=>$new_title,
                    'img_url2'=> $path_r_1,
                    'img_url2_s'=>$path_r_s_1,
                );
                
                $travelnote->update($condition,$newrow);
            }
            if(!empty($value['img_url3']))
            {
                $path = "saestor://travelnote/".$value['author'].'/note/'.$new_title.'/'.$value['date'].'/'.'img3.jpg';
                $path_s = "saestor://travelnote/".$value['author'].'/note/'.$new_title.'/'.$value['date'].'/'.'img3_s.jpg';
                $path_r_2 = "http://travelsky-travelnote.stor.sinaapp.com/".$value['author'].'/note/'.$new_title.'/'.$value['date'].'/'.'img3.jpg';
                $path_r_s_2 = "http://travelsky-travelnote.stor.sinaapp.com/".$value['author'].'/note/'.$new_title.'/'.$value['date'].'/'.'img3_s.jpg';
                
                $file = file_get_contents($value['img_url3']);
                $file_s = file_get_contents($value['img_url3_s']);
                file_put_contents($path,$file);
                file_put_contents($path_s,$file_s);
                
                unlink("saestor://travelnote/".$value['author'].'/note/'.$title.'/'.$value['date'].'/img3.jpg');
 				unlink("saestor://travelnote/".$value['author'].'/note/'.$title.'/'.$value['date'].'/img3_s.jpg'); 
                rmdir("http://travelsky-travelnote.stor.sinaapp.com".$value['author']."/note/".$title.'/'.$valuep['date']);
                
                $condition = array(
                    'author' => $email,
                    'title' => $title,
                    'date' => $value['date'],
                );
                $newrow = array(
                    'title'=>$new_title,
                    'img_url3'=> $path_r_2,
                    'img_url3_s'=>$path_r_s_2,
                );
                
                $travelnote->update($condition,$newrow);
            }
        }
        echo "success";
    }
    
    function my_trip(){
        $guestbook = spClass("guestbook");
        $travelnote = spClass("travelnote_t");
        $email = $this->spArgs('email');
        $jsonarray = array();        
       	$sql = "select id,title,date,location,author,img_url_s from travelnote_t where author='".$email."' order by id desc limit 10";
        //echo $sql;
        if( $result = $travelnote->findSql($sql) ){ 
        foreach($result as  $value){
            $condition = array('email'=>$value['author']);
            $result1 = $guestbook->find($condition);
            $value['author'] = $result1['user'];
            $value['title'] = urlencode($value['title']); 
            $value['date'] = urlencode($value['date']);
            $value['location'] = urlencode($value['location']);
            //echo urldecode("%E5%B9%BF%E5%B7%9E");
            $value['author'] = urlencode($value['author']);
            $value['img_head'] =urlencode("http://travelsky-travelnote.stor.sinaapp.com/".$result1['email']."/head/img_head.jpg");
            $value['img_url_s'] = urlencode($value['img_url_s']);
            $json = array(
                'id'=>$value['id'],
                'title'=>$value['title'],
                'email'=>$result1['email'],
                'date'=>$value['date'],
                'location'=>$value['location'],
                'author'=>$value['author'],
                'img_url_s'=>$value['img_url_s'],
                'img_head'=>$value['img_head'],
            );
            array_push($jsonarray,$json);
        	} 
            $jsonarray1 = array('jsonarray'=>$jsonarray);
            echo urldecode(json_encode($jsonarray1));
        }
    }
    
    function note_click(){
        $travelnote = spClass('travelnote');
        $title = $this->spArgs('title');
        $email = $this->spArgs('email');
        $jsonarray = array();
        $sql = "select id,date,content,img_url1_s from travelnote where title='".$title."' and author='".$email."' order by id desc limit 10";
        //echo $sql;
        if($result = $travelnote->findSql($sql))
        {
            foreach($result as $value)
            {
                $value['date'] = urlencode($value['date']);
                $value['content'] = urlencode($value['content']);
                $value['img_url1_s'] = urlencode($value['img_url1_s']);
                $json = array(
                    'id'=>$value['id'],
                    'date'=>$value['date'],
                    'content'=>$value['content'],
                    'img_url1_s'=>$value['img_url1_s'],
                );
                array_push($jsonarray,$json);
            }
            $jsonarray1 = array('jsonarray'=>$jsonarray);
            echo urldecode(json_encode($jsonarray1));
        }
        else 
            echo "null";
    }
    
    function praise(){														//点赞
        $travelnote = spClass('travelnote_t');
        $title = $this->spArgs('title');
        $email_c = $this->spArgs('email_c');
        $email_a = $this->spArgs('email_a');
        $condition = array(
            'title'=>$title,
            'author'=>$email_a,
        );
        $result = $travelnote->find($condition);
        $result['zang']=$result['zang']+1;
        $newrow = array(
            'zang'=>$result['zang'],
            'collector'=>$result['collector'].$email_c.',',
        );
        $travelnote->update($condition,$newrow);
        echo "success";
    }
    
    function my_collect(){
        $guestbook = spClass("guestbook");
        $travelnote = spClass("travelnote_t");
        $email = $this->spArgs('email');
        $id = $this->spArgs('id');
        $jsonarray = array();
        $sql = "select id,title,date,location,author,img_url_s from travelnote_t where collector like '%".$email."%' order by id desc limit 10";
        if( $result = $travelnote->findSql($sql) ){ 
        foreach($result as  $value){
            $condition = array('email'=>$value['author']);
            $result1 = $guestbook->find($condition);
            $value['author'] = $result1['user'];
            $value['title'] = urlencode($value['title']); 
            $value['date'] = urlencode($value['date']);
            $value['location'] = urlencode($value['location']);
            //echo urldecode("%E5%B9%BF%E5%B7%9E");
            $value['author'] = urlencode($value['author']);
            $value['img_head'] =urlencode("http://travelsky-travelnote.stor.sinaapp.com/".$result1['email']."/head/img_head.jpg");
            $value['img_url_s'] = urlencode($value['img_url_s']);
            $json = array(
                'id'=>$value['id'],
                'title'=>$value['title'],
                'email'=>$result1['email'],
                'date'=>$value['date'],
                'location'=>$value['location'],
                'author'=>$value['author'],
                'img_url_s'=>$value['img_url_s'],
                'img_head'=>$value['img_head'],
            );
            array_push($jsonarray,$json);
        	} 
            $jsonarray1 = array('jsonarray'=>$jsonarray);
            echo urldecode(json_encode($jsonarray1));
        }
    }
    
    function _email() {
        $mail = spClass('spEmail');
        $mailsubject = "[旅游笔记本用户密码找回";//邮件主题
        $mailbody = "尊敬的用户，你的密码为：123456";//邮件内容
        $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
        $mail->sendmail('kobe605899786@qq.com', $mailsubject, $mailbody, $mailtype);
        echo "success";
        }
}	