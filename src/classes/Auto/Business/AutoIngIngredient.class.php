<?php
/*****************************************************************************
 *   Copyright (C) 2006-2009, onPHP's MetaConfiguration Builder.             *
 *   Generated by onPHP-1.0.10.99 at 2012-03-11 01:29:06                     *
 *   This file is autogenerated - do not edit.                               *
 *****************************************************************************/

	abstract class AutoIngIngredient extends IdentifiableObject
	{
		protected $productId = null;
		protected $receiptId = null;
		protected $weight = null;
		protected $count = null;
		protected $comment = null;
		
		/**
		 * @return IngProduct
		**/
		public function getProduct()
		{
			if ($this->productId !== null) {
				return IngProduct::dao()->getById($this->productId);
			}
			
			return null;
		}
		
		public function getProductId()
		{
			return $this->productId;
		}
		
		/**
		 * @return IngIngredient
		**/
		public function setProduct(IngProduct $product)
		{
			$this->productId = $product->getId();
			
			return $this;
		}
		
		/**
		 * @return IngIngredient
		**/
		public function setProductId($id)
		{
			$this->productId = $id;
			
			return $this;
		}
		
		/**
		 * @return IngIngredient
		**/
		public function dropProduct()
		{
			$this->productId = null;
			
			return $this;
		}
		
		/**
		 * @return IngReceipt
		**/
		public function getReceipt()
		{
			if ($this->receiptId !== null) {
				return IngReceipt::dao()->getById($this->receiptId);
			}
			
			return null;
		}
		
		public function getReceiptId()
		{
			return $this->receiptId;
		}
		
		/**
		 * @return IngIngredient
		**/
		public function setReceipt(IngReceipt $receipt)
		{
			$this->receiptId = $receipt->getId();
			
			return $this;
		}
		
		/**
		 * @return IngIngredient
		**/
		public function setReceiptId($id)
		{
			$this->receiptId = $id;
			
			return $this;
		}
		
		/**
		 * @return IngIngredient
		**/
		public function dropReceipt()
		{
			$this->receiptId = null;
			
			return $this;
		}
		
		public function getWeight()
		{
			return $this->weight;
		}
		
		/**
		 * @return IngIngredient
		**/
		public function setWeight($weight)
		{
			$this->weight = $weight;
			
			return $this;
		}
		
		public function getCount()
		{
			return $this->count;
		}
		
		/**
		 * @return IngIngredient
		**/
		public function setCount($count)
		{
			$this->count = $count;
			
			return $this;
		}
		
		public function getComment()
		{
			return $this->comment;
		}
		
		/**
		 * @return IngIngredient
		**/
		public function setComment($comment)
		{
			$this->comment = $comment;
			
			return $this;
		}
	}
?>