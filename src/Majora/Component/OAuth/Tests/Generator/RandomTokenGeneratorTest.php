<?php

namespace {
    $mockRandomPseudoBytes = false;
}

namespace Majora\Component\OAuth\Generator {
    /**
     * override global method to tests if function cannot get a strong hash.
     */
    function openssl_random_pseudo_bytes($length, &$strong)
    {
        global $mockRandomPseudoBytes;
        if (isset($mockRandomPseudoBytes) && $mockRandomPseudoBytes === true) {
            $strong = false;

            return 12345;
        } else {
            return \openssl_random_pseudo_bytes($length, $strong);
        }
    }
}

namespace Majora\Component\OAuth\Tests\Generator {

    use Majora\Component\OAuth\Generator\RandomTokenGenerator;

    /**
     * Unit test class for Majora\Component\OAuth\Generator\RandomTokenGenerator.
     */
    class RandomTokenGeneratorTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * SetUp method overriden to reset global mock boolean.
         */
        public function setUp()
        {
            global $mockRandomPseudoBytes;

            $mockRandomPseudoBytes = false;
        }

        /**
         * Cases provider for token generation.
         */
        public function generateCaseProvider()
        {
            return array(
                'openssl_pseudo_bytes' => array(false, 50, 100),
                'mt_rand_sha_512' => array(true, 64, 100),
            );
        }

        /**
         * Tests generate() method.
         *
         * @dataProvider generateCaseProvider
         */
        public function testGenerate($enableMock, $tokenLength, $iterations)
        {
            global $mockRandomPseudoBytes;

            $mockRandomPseudoBytes = $enableMock;

            $generator = new RandomTokenGenerator('mocked_secret');
            $alreadyGenerated = array();

            for ($i = 0; $i < $iterations; ++$i) {
                $token = $generator->generate('test_seed');

                $this->assertEquals($tokenLength, strlen($token));
                $this->assertNotContains($token, $alreadyGenerated);

                $alreadyGenerated[] = $token;
            }
        }
    }
}
