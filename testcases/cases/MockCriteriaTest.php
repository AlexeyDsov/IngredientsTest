<?php
/***************************************************************************
 *   Copyright (C) 2011 by Alexey Denisov                                  *
 *   alexeydsov@gmail.com                                                  *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *                                                                         *
 ***************************************************************************/

	class MockCriteriaTest extends PHPUnit_Framework_TestCase implements IMockSpawnSupport
	{

		//start *interface IMockSpawnSupport part*
		public function getAny()
		{
			return $this->any();
		}

		public function getNever()
		{
			return $this->never();
		}

		public function getOnce()
		{
			return $this->once();
		}

		public function getExactly($callTimes)
		{
			return $this->exactly($callTimes);
		}

		public function getAt($callTime)
		{
			return $this->at($callTime);
		}

		public function getReturnArgument($argumentNumber)
		{
			return $this->returnArgument($argumentNumber);
		}

		public function getReturnCallback(Closure $callBack)
		{
			return $this->returnCallback($callBack);
		}

		public function getReturnValue($value)
		{
			return $this->returnValue($value);
		}
		//end *interface IMockSpawnSupport part*

		/**
		 * @test
		 */
		public function returnDefaultNullValue()
		{
			$mock = $this->getService()->spawn($this);
			$this->assertNull($mock->sub(1, 2), 'Mock by default must return null in any method');
		}

		/**
		 * @test
		 */
		public function returnValueInMethod()
		{
			$mock = $this->getService()->
				addMethod(MockMethod::create('sum')->setReturn(3)->setCallTimes(1))->
				spawn($this);
			$this->assertEquals(3, $mock->sum(1, 2), 'Mock must return 3');
		}

		/**
		 * @test
		 */
		public function returnValueArgument()
		{
			$mock = $this->getService()->
				addMethod(MockMethod::create('sum')->setReturnArgument(0))->
				addMethod(MockMethod::create('div')->setReturnArgument(1))->
				spawn($this);
			$this->assertEquals(10, $mock->sum(10, 20));
			$this->assertEquals(20, $mock->div(10, 20));
		}

		/**
		 * @test
		 */
		public function thowException()
		{
			$mock = $this->getService()->
				addMethod(
					MockMethod::create('div')->
						setThrowException(new Exception($excMsg = 'exception message', $excCode = 667))
				)->
				spawn($this);

			try {
				$result = $mock->div(100, 0);
				$this->assertTrue(false, 'in method before must be thrown exception');
			} catch (Exception $e) {
				$this->assertEquals($excMsg, $e->getMessage(), 'wrong exception msg');
				$this->assertEquals($excCode, $e->getCode(), 'wrong exception code');
			}
		}

		/**
		 * @test
		 */
		public function callMethodNever()
		{
			$mock = $this->getService()->
				addMethod(MockMethod::create('sum')->setCallTimes(0))->
				spawn($this);
		}

		/**
		 * @test
		 */
		public function callMethodThreeTimes()
		{
			$mock = $this->getService()->
				addMethod(MockMethod::create('sum')->setCallTimes(3))->
				spawn($this);
			$this->assertNull($mock->sum(1, 1));
			$this->assertNull($mock->sum(1, 1));
			$this->assertNull($mock->sum(1, 1));
		}

		/**
		 * @test
		 */
		public function testLambdaFunction()
		{
			$mock = $this->getService()->
				addMethod(MockMethod::create('sum')->setReturnFunction(true)->setFunction(function($a, $b) {return 5;}))->
				spawn($this);

			$this->assertEquals(5, $mock->sum(10, 10));
		}

		/**
		 * @test
		 */
		public function testLambdaFunctionAndReturnSelf()
		{
			//stupid hack because in default phpunit they clone all objects which go into callback function :(((
			$subObject = new SplStack();
			$object = new SplStack();
			$object->push($subObject);
			//end hack

			$mock = $this->getService()->
				addMethod(
					MockMethod::create('div')->
						setReturnSelf(true)->
						setFunction(function(SplStack $object) {
							$subObject = $object->pop();
							$subObject->push('hou hou hou');
						})
				)->
				spawn($this);

			$this->assertEquals($mock, $mock->div($object, 10));
			$this->assertEquals('hou hou hou', $subObject->pop());
		}

		/**
		 * @test
		 */
		public function callMethodAtTime()
		{
			$mock = $this->getService()->
				addMethod(MockMethodAt::create('sum', 0)->setReturn(1))->
				addMethod(
					MockMethodAt::create('sum', 1)->
						setReturnFunction(true)->
						setFunction(function() {return 2;})
				)->
				addMethod(MockMethodAt::create('sum', 2)->setThrowException(new Exception()))->
				addMethod(MockMethodAt::create('sum', 3)->setReturnArgument(1))->
				spawn($this);
			$this->assertEquals(1, $mock->sum(1, 2));
			$this->assertEquals(2, $mock->sum(1, 2));
			try {
				$this->assertEquals(3, $mock->sum(1, 2));
				$this->assertFalse(true, 'Mustn\'t be reached');
			} catch (Exception $e) { /* all ok */ }
			$this->assertEquals(4, $mock->sum(1, 4));
		}

		/**
		 * @test
		 */
		public function failOnManyReturns()
		{
			$this->setExpectedException('WrongStateException');

			$mock = $this->getService()->
				addMethod(
					MockMethod::create('sum')->
						setReturnArgument(0)->
						setThrowException(new Exception())->
						setReturnSelf(true)->
						setReturnFunction(true)->
						setFunction(function() {return 5;})
				)->
				spawn($this);
		}

		/**
		 * @test
		 */
		public function failSetDoubleMethods()
		{
			$this->setExpectedException('WrongArgumentException');

			$mock = $this->getService()->
				addMethod(MockMethod::create('sum'))->
				addMethod(MockMethod::create('sum'));
		}

		/**
		 * @test
		 */
		public function failSetDoubleAtMethods()
		{
			$this->setExpectedException('WrongArgumentException');

			$mock = $this->getService()->
				addMethod(MockMethodAt::create('sum', 1))->
				addMethod(MockMethodAt::create('sum', 1));
		}

		/**
		 * @test
		 */
		public function failSetDifferentMethodsOne()
		{
			$this->setExpectedException('WrongArgumentException');

			$mock = $this->getService()->
				addMethod(MockMethod::create('sum'))->
				addMethod(MockMethodAt::create('sum'));
		}

		/**
		 * @test
		 */
		public function failSetDifferentMethodsTwo()
		{
			$this->setExpectedException('WrongArgumentException');

			$mock = $this->getService()->
				addMethod(MockMethodAt::create('sum', 1))->
				addMethod(MockMethod::create('sum'));
		}

		/**
		 * @return MockCriteria;
		 */
		protected function getService()
		{
			return MockCriteria::create('MockCriteriaTestObject');
		}
	}
?>