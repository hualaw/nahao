<<<<<<< HEAD
<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *	   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package log4php
 */

/**
 * Returns the name of the function or method from which the logging request 
 * was issued. 
 * 
 * @package log4php
 * @subpackage pattern
 * @version $Revision: 1326626 $
 * @since 2.3
 */
class LoggerPatternConverterMethod extends LoggerPatternConverter {

	public function convert(LoggerLoggingEvent $event) {
		return $event->getLocationInformation()->getMethodName();
	}
}
=======
<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *	   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package log4php
 */

/**
 * Returns the name of the function or method from which the logging request 
 * was issued. 
 * 
 * @package log4php
 * @subpackage pattern
 * @version $Revision: 1326626 $
 * @since 2.3
 */
class LoggerPatternConverterMethod extends LoggerPatternConverter {

	public function convert(LoggerLoggingEvent $event) {
		return $event->getLocationInformation()->getMethodName();
	}
}
>>>>>>> 1cfaf498161285f3d25946b9e3aa8be4103025d6
