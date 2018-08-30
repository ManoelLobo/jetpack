<?php

if (!defined('WHMCS')) {
	define('WHMCS', true);
}

require_once __DIR__ . '/../../../../partner/WHMCS/modules/servers/jetpack/jetpack.php';


use PHPUnit\Framework\TestCase as TestCase;

class JetpackModuleTest extends TestCase {


	protected $moduleName = 'jetpack';

	protected $allowed_plans = ['free', 'personal', 'premium', 'professional'];

	protected $params = [
		'customfields' => [
			'Plan' => 'Personal',
			'Site URL' => 'testdomain.com',
			'Local User' => 'admin',
			'jetpack_provisioning_details' => ''
		],
		'configoption1' => 12345,
		'configoption2' => 'aclientsecret'
	];

	/**
	 * Verify that the required core functions are present in the module
	 */
	public function testRequiredCoreFunctionsExists()
	{
		$this->assertTrue(function_exists($this->moduleName . '_ConfigOptions'));
		$this->assertTrue(function_exists($this->moduleName . '_CreateAccount'));
		$this->assertTrue(function_exists($this->moduleName . '_TerminateAccount'));
	}

	/**
	 * Test the validate required fields function with good params
	 */
	public function test_validate_required_fields()
	{
		$this->assertTrue(validate_required_fields(array_merge($this->params)));
	}

	/**
	 * Test that an error string is returned if the client id is missing
	 * in the config options
	 */
	public function test_validate_required_fields_missing_client_id()
	{
		$params_missing_config = $this->get_params_missing_config_options('configoption1');
		$this->assertContains('JETPACK MODULE', validate_required_fields($params_missing_config));
	}

	/**
 	* Test that an error string is returned if the client secret is missing
 	* in the config options
 	*/
	public function test_validate_required_fields_missing_client_secret()
	{
		$params_missing_config = $this->get_params_missing_config_options('configoption2');
		$this->assertContains('JETPACK MODULE', validate_required_fields($params_missing_config));
	}

	/**
	 * Test that an error string is returned if the one of the required custom
	 * fields is missing in the config options
	 */
	public function test_validate_required_fields_missing_required_custom_field()
	{
		foreach($this->params['customfields'] as $key => $customfield) {
			$params_missing_config = $this->get_params_missing_custom_field($key);
			$this->assertContains('JETPACK MODULE', validate_required_fields($params_missing_config));
		}

	}

	/**
	 * Test that an error string is returned if the Jetpack plan type specified is
	 * not valid
	 */
	public function test_validate_required_fields_bad_plan()
	{
		$bad_plan = $this->params;
		$bad_plan['customfields']['Plan'] = 'Unknown';
		$this->assertContains('JETPACK MODULE', validate_required_fields($bad_plan));
	}

	/**
	 * Modify the config options and remove either the client id
	 * or client secret
	 * @param $configToRemove
	 * @return array
	 */
	public function get_params_missing_config_options($configToRemove)
	{
		$modified_params = $this->params;
		unset($modified_params[$configToRemove]);
		return $modified_params;
	}

	/**
	 * Modify the params and remove one of the required custom fields
	 *
	 * @param $customFieldToRemove
	 * @return array
	 */
	public function get_params_missing_custom_field($customFieldToRemove)
	{
		$modified_params = $this->params;
		unset($modified_params['customfields'][$customFieldToRemove]);
		return $modified_params;
	}

}