<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hallmark_model extends CI_Model
{
	public function __construct()
    {
        parent::__construct();
       $this->sessionData=$this->session->userdata("company1");
	   $this->branch_id=$this->sessionData['branch_id'];
	   $this->trdb=$this->load->database('tdb',true);
    }
	public function insert_customeradd()
        {
          
         $name=$this->input->post("name");
         $gstin_number=$this->input->post("gstin_number");
		 $pincode=$this->input->post("pincode");
         $city=$this->input->post("city");
		 $license_number=$this->input->post("license_number");
       
         $address1=$this->input->post("address1");
		 $address2=$this->input->post("address2");
		 $address3=$this->input->post("address3");
         $ex_date=date("Y-m-d",strtotime($this->input->post("ex_date")));
         $mobile=$this->input->post("mobile");  
		
         $insert=array(
		
		 "customer_name"=>$name,
          "GST_NO"=>$gstin_number,
		  "Address1"=>$address1,
		  "Address2"=>$address2,
		  "Address3"=>$address3,
		  "pincode"=>$pincode,
		  "city"=>$city,
		 "License_no"=>$license_number,
		 "mobile_number1"=>$mobile,
		 
          "License_exp_date"=>$ex_date,
		  "status"=>1,
		 );
         
         
        
        
        $this->db->select('*');
	    $this->db->from("$this->mdb.hallmark_customer");
		$this->db->where('License_no',$license_number);
		
	    $query=$this->db->get();
		
		if($query->num_rows()==0)
		{
			 $this->db->insert("$this->mdb.hallmark_customer",$insert);
			 return $this->db->insert_id();
			
		}
		return 2;
         
        }
        
        public function edit_customeradd($id)
        {
          
        $name=$this->input->post("name");
         $gstin_number=$this->input->post("gstin_number");
		 $pincode=$this->input->post("pincode");
         $city=$this->input->post("city");
		 $license_number=$this->input->post("license_number");
       
         $address1=$this->input->post("address1");
		 $address2=$this->input->post("address2");
		 $address3=$this->input->post("address3");
         $ex_date=date("Y-m-d",strtotime($this->input->post("ex_date")));
         $mobile=$this->input->post("mobile");  
		
         $insert=array(
		
		 "customer_name"=>$name,
          "GST_NO"=>$gstin_number,
		  "Address1"=>$address1,
		  "Address2"=>$address2,
		  "Address3"=>$address3,
		  "pincode"=>$pincode,
		  "city"=>$city,
		 "License_no"=>$license_number,
		 "mobile_number1"=>$mobile,
		 
          "License_exp_date"=>$ex_date,
		  "status"=>1,
		 );
         
			$this->db->select('*');
			$this->db->from("$this->mdb.hallmark_customer");
			$this->db->where("id",$id);
			//$this->db->where('name',$name);	//Old Code doen't know the reason for this condition
			$query=$this->db->get();
		 
			if($query->num_rows()==1)
			{
				$this->db->where("id",$id);
				$this->db->update("$this->mdb.hallmark_customer",$insert);
				return 1;
			}
			return 2;
         
        }
		public function insert_Blog()
        {
        
         $cat=$this->input->post("category");   
        
         $name=$this->input->post("name");  
         
        
		 $product=array(
					"product_category_id"=>$cat,					
					"product_name"=>$name,					
					'status'=>1);
				
		$this->db->select('*');
	    $this->db->from("$this->mdb.hallmark_product");
	    $this->db->where('product_name',$name);
	    $query=$this->db->get();
		 
		if($query->num_rows()==0)
		{
			
				$this->db->insert("$this->mdb.hallmark_product",$product);
				return 1;
			
		}
	    return 2;
         
        }
        
        public function edit_product($id)
        {
        
			 $cat=$this->input->post("category");   
        
         $name=$this->input->post("name");  
         
        
		 $product=array(
					"product_category_id"=>$cat,					
					"product_name"=>$name,					
					'status'=>1);
				
			$this->db->select('*');
			$this->db->from("$this->mdb.hallmark_product");
			$this->db->where("id!=",$id);
			$this->db->where('product_name',$name);
			$query=$this->db->get();
			 
			if($query->num_rows()==0)
			{
				
					$this->db->where("id",$id);
					$this->db->update("$this->mdb.hallmark_product",$product);
					return 1;
				
			}			
			return 2;
		}
		public function insert_priceadd()
        {
        
         $fromaty=$this->input->post("fromaty");        
         $toqty=$this->input->post("toqty"); 
		 $amount=$this->input->post("amount");    
         $date=date('Y-m-d');
		 $lumpsum=0;
		if($_POST['Lumpsum'])
		{
			$lumpsum=1;
		}
		
		 $priceedata=array(
					"from_qty"=>$fromaty,					
					"to_qty"=>$toqty,									
					"amount"=>$amount,
					"Lumpsum_status"=>$lumpsum,					
					"from_date"=>$date,
					'status'=>1);
		
		
		$this->db->select('*');
	    $this->db->from("$this->mdb.hallmark_price");
		$this->db->where('to_qty>=',$fromaty);
		$this->db->where('status',1);
	    $query=$this->db->get();
		 
		if($query->num_rows()==0)
		{
			
				$this->db->insert("$this->mdb.hallmark_price",$priceedata);
				return 1;
			
		}
	    return 2;
         
        }
        
        public function edit_price($id)
        {
         $fromaty=$this->input->post("fromaty");        
         $toqty=$this->input->post("toqty"); 
		 $amount=$this->input->post("amount");    
         $date=date('Y-m-d');
		 $lumpsum=0;
		if($_POST['Lumpsum'])
		{
			$lumpsum=1;
		}
		
		 $priceedata=array(
					"from_qty"=>$fromaty,					
					"to_qty"=>$toqty,									
					"amount"=>$amount,
					"Lumpsum_status"=>$lumpsum,					
					"from_date"=>$date,
					'status'=>1);
		$this->db->set("status",2);
		$this->db->set("to_date",$date);
		$this->db->where("id",$id);		
		$this->db->update("$this->mdb.hallmark_price");		
		$this->db->select('*');
	    $this->db->from("$this->mdb.hallmark_price");
		$this->db->where('to_qty>=',$fromaty);
		$this->db->where('status',1);
	    $query=$this->db->get();
		 
		if($query->num_rows()==0)
		{
			
				$this->db->insert("$this->mdb.hallmark_price",$priceedata);
				return 1;
			
		}
	    return 2;
         
        }
		public function insert_purityadd()
        {
        
          
        
         $name=$this->input->post("name");  
         
        
		 $product=array(
									
					"purity"=>$name,					
					'status'=>1);
				
		$this->db->select('*');
	    $this->db->from("$this->mdb.hallmark_purity");
	    $this->db->where('purity',$name);
	    $query=$this->db->get();
		 
		if($query->num_rows()==0)
		{
			
				$this->db->insert("$this->mdb.hallmark_purity",$product);
				return 1;
			
		}
	    return 2;
         
        }
        
        public function edit_purity($id)
        {
        
			 
        
         $name=$this->input->post("name");  
         
        
		$product=array(
									
					"purity"=>$name,					
					'status'=>1);
				
			$this->db->select('*');
			$this->db->from("$this->mdb.hallmark_purity");
			$this->db->where("id!=",$id);
			$this->db->where('purity',$name);
			$query=$this->db->get();
			 
			if($query->num_rows()==0)
			{
				
					$this->db->where("id",$id);
					$this->db->update("$this->mdb.hallmark_purity",$product);
					return 1;
				
			}			
			return 2;
		}
}