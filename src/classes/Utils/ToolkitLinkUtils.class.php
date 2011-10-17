<?php
	/**
	 * Утилита для генерации url/имени диалогового окна на информацию/редактирование/логи объекта
	 */
	class ToolkitLinkUtils implements IServiceLocatorSupport {

		/**
		 * @var IServiceLocator
		 */
		protected $serviceLocator = null;
		protected $logClassName = null;

		/**
		 * @return ToolkitLinkUtils
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @param IServiceLocator $serviceLocator
		 * @return ToolkitLinkUtils
		 */
		public function setServiceLocator(IServiceLocator $serviceLocator)
		{
			$this->serviceLocator = $serviceLocator;
			return $this;
		}

		/**
		 * @return IServiceLocator
		 */
		public function getServiceLocator()
		{
			return $this->serviceLocator;
		}
		
		/**
		 * @param string $logClassName
		 * @return ToolkitLinkUtils 
		 */
		public function setLogClassName($logClassName)
		{
			$this->logClassName = $logClassName;
			return $this;
		}

		/**
		 * Проверяет поддерживает ли эта утилита тип переданного объекта
		 * @param mixed $object
		 * @param string $method префикс действия пользователя, на которое проверяются у него права
		 * @return boolean
		 */
		public function isObjectSupported($object, $method)
		{
			if (is_object($object)) {
				$object = get_class($object);
			}

			if ($user = $this->serviceLocator->get('admin')->getUser()) {
				$action = $method.'.'.$object;
				return $this->serviceLocator->get('permissionManager')->hasPermission($user, $action);
			}
			return false;
		}

		/**
		 * Создает url к контроллеру объекта, отвечающему за показ, редактирование
		 * Параметр $method если указан, то он определяет префикс действия пользователя.
		 *   Если не указан, то префикс действия берется из $urlParams['action']
		 *   Если и его нет, то тогда префикс = 'info'
		 * @param mixed $object
		 * @param array $urlParams
		 * @param string $method
		 * @return string
		 */
		public function getUrl($object, $urlParams = array(), $method = null)
		{
			Assert::isTrue(is_object($object) || is_string($object), '$object is not an object or string');
			if (is_object($object)) {
				Assert::isInstance($object, 'IdentifiableObject', '$object is not identifiable object');
			}
			$method = $method ?: (isset($urlParams['action']) ? $urlParams['action'] : 'info');
			Assert::isTrue($this->isObjectSupported($object, $method), 'not supported action with prefix: '.$method);

			$controllerName = is_object($object) ? get_class($object) : $object;
			$urlParams += array(
				'area' => $controllerName,
				'id' => (is_object($object) ? $object->getId() : ''),
			);

			return PATH_WEB_URL . http_build_query($urlParams);
		}

		/**
		 * Возвращает url к логам редактирования объекта через toolkit
		 * @param mixed $object
		 * @param array $urlParams
		 * @return type
		 */
		public function getUrlLog($object, $urlParams = array())
		{
			Assert::isTrue(is_object($object), '$object is not an object');
			Assert::isInstance($object, 'IdentifiableObject', '$object is not identifiable object');
			Assert::isTrue($this->isObjectSupported($this->logClassName, 'info'), 'not supported logs ');

			$urlParams += array(
				'area' => "{$this->logClassName}List",
				'action' => 'list',
				'objectName' => array(
					'eq' => get_class($object),
				),
				'objectId' => array(
					'eq' => $object->getId(),
				),
				'id' => array(
					'sort' => 'desc',
					'order' => '1',
				)
			);

			return PATH_WEB_URL . http_build_query($urlParams);
		}

		/**
		 * Возвращает имя диалогового окна, в которм должна происходить работа с объектом
		 * @param mixed $object
		 * @param string $method
		 * @return string
		 */
		public function getDialogName($object, $method = null)
		{
			Assert::isTrue(is_object($object) || is_string($object), '$object is not an object or string');
			if (is_object($object)) {
				Assert::isInstance($object, 'IdentifiableObject', '$object is not identifiable object');
			}
			Assert::isTrue($this->isObjectSupported($object, $method ?: 'info'), 'not supported object');

			$objectClassName = is_object($object) ? get_class($object) : $object;
			return $objectClassName . (is_object($object) ? $object->getId() : '');
		}
	}
?>