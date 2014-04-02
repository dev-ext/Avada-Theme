<?php

	class RevSlide extends UniteElementsBaseRev{
		
		private $id;
		private $sliderID;
		private $slideOrder;
		
		private $imageUrl;
		private $imageFilepath;
		private $imageFilename;
		
		private $params;
		private $arrLayers;
		
		public function __construct(){
			parent::__construct();
		}
		
		/**
		 * 
		 * init slide by db record
		 */
		public function initByData($record){
			
			$this->id = $record["id"];
			$this->sliderID = $record["slider_id"];
			$this->slideOrder = $record["slide_order"];
			
			$params = $record["params"];
			$params = (array)json_decode($params);
			
			$layers = $record["layers"];
			$layers = (array)json_decode($layers);
			$layers = UniteFunctionsRev::convertStdClassToArray($layers);
			
			//set image path, file and url
			$this->imageUrl = UniteFunctionsRev::getVal($params, "image");
			
			$this->imageFilepath = UniteFunctionsWPRev::getImagePathFromURL($this->imageUrl);
		    $realPath = UniteFunctionsWPRev::getPathContent().$this->imageFilepath;
		    
		    if(file_exists($realPath) == false || is_file($realPath) == false)
		    	$this->imageFilepath = "";
		    
			$this->imageFilename = basename($this->imageUrl);
			
			$this->params = $params;
			$this->arrLayers = $layers;	
		}
		
		
		/**
		 * 
		 * init the slider by id
		 */
		public function initByID($slideid){
			UniteFunctionsRev::validateNumeric($slideid,"Slide ID");
			$slideid = $this->db->escape($slideid);
			$record = $this->db->fetchSingle(GlobalsRevSlider::$table_slides,"id=$slideid");
			
			$this->initByData($record);
		}
		
		/**
		 * 
		 * get slide ID
		 */
		public function getID(){
			return($this->id);
		}
		
		
		/**
		 * 
		 * get slide order
		 */
		public function getOrder(){
			$this->validateInited();
			return($this->slideOrder);
		}
		
		
		/**
		 * 
		 * get layers in json format
		 */
		public function getLayers(){
			$this->validateInited();
			return($this->arrLayers);
		}
		
		/**
		 * 
		 * modify layer links for export
		 */
		public function getLayersForExport(){
			$this->validateInited();
			$arrLayersNew = array();
			foreach($this->arrLayers as $key=>$layer){
				$imageUrl = UniteFunctionsRev::getVal($layer, "image_url");
				if(!empty($imageUrl))
					$layer["image_url"] = UniteFunctionsWPRev::getImagePathFromURL($layer["image_url"]);
					
				$arrLayersNew[] = $layer;
			}
			
			return($arrLayersNew);
		}
		
		/**
		 * 
		 * get params for export
		 */
		public function getParamsForExport(){
			$arrParams = $this->getParams();
			$urlImage = UniteFunctionsRev::getVal($arrParams, "image");
			if(!empty($urlImage))
				$arrParams["image"] = UniteFunctionsWPRev::getImagePathFromURL($urlImage);
			
			return($arrParams);
		}
		
		
		/**
		 * normalize layers text, and get layers
		 * 
		 */
		public function getLayersNormalizeText(){
			$arrLayersNew = array();
			foreach ($this->arrLayers as $key=>$layer){
				$text = $layer["text"];
				$text = addslashes($text);
				$layer["text"] = $text;
				$arrLayersNew[] = $layer;
			}
			
			return($arrLayersNew);
		}
		

		/**
		 * 
		 * get slide params
		 */
		public function getParams(){
			$this->validateInited();
			return($this->params);
		}

		
		/**
		 * 
		 * get parameter from params array. if no default, then the param is a must!
		 */
		function getParam($name,$default=null){
			
			if($default === null){
				if(!array_key_exists($name, $this->params))
					UniteFunctionsRev::throwError("The param <b>$name</b> not found in slide params.");
				$default = "";
			}
				
			return UniteFunctionsRev::getVal($this->params, $name,$default);
		}
		
		
		/**
		 * 
		 * get image filename
		 */
		public function getImageFilename(){
			return($this->imageFilename);
		}
		
		
		/**
		 * 
		 * get image filepath
		 */
		public function getImageFilepath(){
			return($this->imageFilepath);
		}
		
		/**
		 * 
		 * get image url
		 */
		public function getImageUrl(){
			return($this->imageUrl);
		}
		
		
		/**
		 * 
		 * get the slider id
		 */
		public function getSliderID(){
			return($this->sliderID);
		}
		
		/**
		 * 
		 * validate that the slider exists
		 */
		private function validateSliderExists($sliderID){
			$slider = new RevSlider();
			$slider->initByID($sliderID);
		}
		
		/**
		 * 
		 * validate that the slide is inited and the id exists.
		 */
		private function validateInited(){
			if(empty($this->id))
				UniteFunctionsRev::throwError("The slide is not inited!!!");
		}
		
		
		/**
		 * 
		 * create the slide (from image)
		 */
		public function createSlide($sliderID,$urlImage){
			//get max order
			$slider = new RevSlider();
			$slider->initByID($sliderID);
			$maxOrder = $slider->getMaxOrder();
			$order = $maxOrder+1;
			
			$params = array();
			$params["image"] = $urlImage;
			$jsonParams = json_encode($params);
			
			$arrInsert = array("params"=>$jsonParams,
			           		   "slider_id"=>$sliderID,
								"slide_order"=>$order,
								"layers"=>""
						);
			
			$slideID = $this->db->insert(GlobalsRevSlider::$table_slides, $arrInsert);
			
			return($slideID);
		}
		
		/**
		 * 
		 * update slide image from data
		 */
		public function updateSlideImageFromData($data){
			
			$slideID = UniteFunctionsRev::getVal($data, "slide_id");			
			$this->initByID($slideID);
			
			$urlImage = UniteFunctionsRev::getVal($data, "url_image");
			UniteFunctionsRev::validateNotEmpty($urlImage);
			
			$arrUpdate = array();
			$arrUpdate["image"] = $urlImage;
			$this->updateParamsInDB($arrUpdate);
			
			return($urlImage);
		}
		
		/**
		 * 
		 * update slide parameters in db
		 */
		private function updateParamsInDB($arrUpdate){
			
			$this->params = array_merge($this->params,$arrUpdate);
			$jsonParams = json_encode($this->params);
			
			$arrDBUpdate = array("params"=>$jsonParams);
			
			$this->db->update(GlobalsRevSlider::$table_slides,$arrDBUpdate,array("id"=>$this->id));
		}

		
		/**
		 * 
		 * sort layers by order
		 */
		private function sortLayersByOrder($layer1,$layer2){
			$layer1 = (array)$layer1;
			$layer2 = (array)$layer2;
			
			$order1 = UniteFunctionsRev::getVal($layer1, "order",1);
			$order2 = UniteFunctionsRev::getVal($layer2, "order",2);
			if($order1 == $order2)
				return(0);
			
			return($order1 > $order2);
		}
		
		
		/**
		 * 
		 * go through the layers and fix small bugs if exists
		 */
		private function normalizeLayers($arrLayers){
			
			usort($arrLayers,array($this,"sortLayersByOrder"));
			
			$arrLayersNew = array();
			foreach ($arrLayers as $key=>$layer){
				
				$layer = (array)$layer;
				
				//set type
				$type = UniteFunctionsRev::getVal($layer, "type","text");
				$layer["type"] = $type;
				
				//normalize position:
				$layer["left"] = round($layer["left"]);
				$layer["top"] = round($layer["top"]);
				
				//unset order
				unset($layer["order"]);
				
				//modify text
				$layer["text"] = stripcslashes($layer["text"]);
				
				$arrLayersNew[] = $layer;
			}
			
			return($arrLayersNew);
		}  
		
		
		
		/**
		 * 
		 * normalize params
		 */
		private function normalizeParams($params){
			
			$urlImage = UniteFunctionsRev::getVal($params, "image_url");
			
			$params["image"] = $urlImage;
			unset($params["image_url"]);
			
			if(isset($params["video_description"]))
				$params["video_description"] = UniteFunctionsRev::normalizeTextareaContent($params["video_description"]);
			
			return($params);
		}
				
		
		/**
		 * 
		 * update slide from data
		 * @param $data
		 */
		public function updateSlideFromData($data){
			
			$slideID = UniteFunctionsRev::getVal($data, "slideid");
			$this->initByID($slideID);
			
			//treat params
			$params = UniteFunctionsRev::getVal($data, "params");
			$params = $this->normalizeParams($params);
			
			//treat layers
			$layers = UniteFunctionsRev::getVal($data, "layers");
			if(gettype($layers) == "string"){
				$layers = stripslashes($layers);
				$layers = json_decode($layers);
				$layers = UniteFunctionsRev::convertStdClassToArray($layers);
			}
			
			if(empty($layers) || gettype($layers) != "array")
				$layers = array();
			
			$layers = $this->normalizeLayers($layers);
			
			$arrUpdate = array();
			$arrUpdate["layers"] = json_encode($layers);
			$arrUpdate["params"] = json_encode($params);
			
			$this->db->update(GlobalsRevSlider::$table_slides,$arrUpdate,array("id"=>$this->id));
		}
		
		
		/**
		 * 
		 * delete slide from data
		 */
		public function deleteSlideFromData($data){
			$slideID = UniteFunctionsRev::getVal($data, "slideID");
			$this->initByID($slideID);
			$this->db->delete(GlobalsRevSlider::$table_slides,"id='$slideID'");
		}
		
		/**
		 * 
		 * set params from client
		 */
		public function setParams($params){
			$params = $this->normalizeParams($params);
			$this->params = $params;
		}
		
		/**
		 * 
		 * set layers from client
		 */
		public function setLayers($layers){
			$layers = $this->normalizeLayers($layers);
			$this->arrLayers = $layers;
		}
		
		
		/**
		/* toggle slide state from data
		 */
		public function toggleSlideStatFromData($data){
			
			$slideID = UniteFunctionsRev::getVal($data, "slide_id");
			$this->initByID($slideID);
			
			$state = $this->getParam("state","published");
			$newState = ($state == "published")?"unpublished":"published";
			
			$arrUpdate = array();
			$arrUpdate["state"] = $newState;
			
			$this->updateParamsInDB($arrUpdate);
			
			return($newState);
		}
		
		
	}
	
?>