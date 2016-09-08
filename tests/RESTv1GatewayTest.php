<?php
/**
 * Copyright 2016 Tom Walder
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Tests for REST API v1 Gateway
 *
 * @author Tom Walder <tom@docnet.nu>
 */
class RESTv1GatewayTest extends \PHPUnit_Framework_TestCase
{

    private $str_expected_url;
    private $arr_expected_payload;

    private function initTestHttpClient($str_expected_url, $arr_expected_payload)
    {
        $this->str_expected_url = $str_expected_url;
        $this->arr_expected_payload = $arr_expected_payload;
        return new FakeGuzzleClient();
    }

    private function initTestGateway()
    {
        return $this->getMockBuilder('\\GDS\\Gateway\\RESTv1')->setMethods(['initHttpClient'])->setConstructorArgs(['DatasetTest'])->getMock();
    }

    /**
     * @todo Validate the payloads
     *
     * @param FakeGuzzleClient $obj_http
     */
    private function validateHttpClient(\FakeGuzzleClient $obj_http)
    {
        $this->assertEquals($this->str_expected_url, $obj_http->getPostedUrl());
        // $this->assertEquals($this->arr_expected_payload, $obj_http->getPostedParams());
    }

    public function testTransaction()
    {
        $obj_http = $this->initTestHttpClient('https://datastore.googleapis.com/v1/projects/DatasetTest:beginTransaction', []);
        $obj_gateway = $this->initTestGateway()->setHttpClient($obj_http);

        $obj_gateway->beginTransaction();

        $this->validateHttpClient($obj_http);
    }


    public function testDelete()
    {
        $obj_http = $this->initTestHttpClient('https://datastore.googleapis.com/v1/projects/DatasetTest:commit', ['json' => (object)[]]);
        $obj_gateway = $this->initTestGateway()->setHttpClient($obj_http);

        $obj_store = new \GDS\Store('Test', $obj_gateway);

        $obj_entity = (new GDS\Entity())->setKeyId('123456789');
        $obj_store->delete([$obj_entity]);

        $this->validateHttpClient($obj_http);
    }


}