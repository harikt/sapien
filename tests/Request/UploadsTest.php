<?php
namespace Sapien\Request;

use Sapien\Request;

class UploadsTest extends \PHPUnit\Framework\TestCase
{
    public function testNoFiles()
    {
        $_FILES = null;
        $request = new Request();
        $this->assertEmpty($request->uploads);
    }

    public function testTrivial()
    {
        $request = new Request(globals: ['_FILES' => $this->trivialFiles()]);
        $uploads = $request->uploads;

        $expect = [
            'name' => '',
            'fullPath' => '',
            'type' => '',
            'size' => 0,
            'tmpName' => '',
            'error' => 4,
        ];

        $this->assertSame($expect, $uploads['foo1']->asArray());
        $this->assertSame($expect, $uploads['foo2']->asArray());
        $this->assertSame($expect, $uploads['foo3']->asArray());
        $this->assertSame($expect, $uploads['bar'][0]->asArray());
        $this->assertSame($expect, $uploads['bar'][1]->asArray());
        $this->assertSame($expect, $uploads['bar'][2]->asArray());
        $this->assertSame($expect, $uploads['baz']['baz1']->asArray());
        $this->assertSame($expect, $uploads['baz']['baz2']->asArray());
        $this->assertSame($expect, $uploads['baz']['baz3']->asArray());

        $actual = $request->uploads['foo1']->move('/tmp');
        $this->assertFalse($actual);
    }

    public function testComplex()
    {
        $request = new Request(globals: ['_FILES' => $this->complexFiles()]);
        $uploads = $request->uploads;

        $expect = [
            'name' => 'foo_name',
            'fullPath' => 'foo_name',
            'type' => 'foo_type',
            'size' => 0,
            'tmpName' => 'foo_tmp_name',
            'error' => 4,
        ];

        $this->assertSame($expect, $uploads['foo']->asArray());

        foreach ([1, 2, 3] as $i) {
            foreach (['a', 'b', 'c'] as $j) {
                $expect = [
                  'name' => "dib{$i}{$j}_name",
                  'fullPath' => "dib{$i}{$j}_name",
                  'type' => "dib{$i}{$j}_type",
                  'size' => 0,
                  'tmpName' => "dib{$i}{$j}_tmp_name",
                  'error' => 4,
                ];

                $k1 = "dib{$i}";
                $k2 = "dib{$i}{$j}";

                $this->assertSame($expect, $uploads['dib'][$k1][$k2]->asArray());
            }
        }
    }

    public function trivialFiles()
    {
        return [
            'foo1' => [
                'error' => 4,
                'name' => '',
                'full_path' => '',
                'size' => 0,
                'tmp_name' => '',
                'type' => '',
          ],
            'foo2' => [
                'error' => 4,
                'name' => '',
                'full_path' => '',
                'size' => 0,
                'tmp_name' => '',
                'type' => '',
          ],
            'foo3' => [
                'error' => 4,
                'name' => '',
                'full_path' => '',
                'size' => 0,
                'tmp_name' => '',
                'type' => '',
          ],
            'bar' => [
                'name' => [
                     0 => '',
                     1 => '',
                     2 => '',
                ],
                'full_path' => [
                      0 => '',
                      1 => '',
                      2 => '',
                ],
                'type' => [
                    0 => '',
                    1 => '',
                    2 => '',
                ],
                'tmp_name' => [
                    0 => '',
                    1 => '',
                    2 => '',
                ],
                'error' => [
                    0 => 4,
                    1 => 4,
                    2 => 4,
                ],
                'size' => [
                    0 => 0,
                    1 => 0,
                    2 => 0,
                ],
            ],
            'baz' => [
                'name' => [
                    'baz1' => '',
                    'baz2' => '',
                    'baz3' => '',
                ],
                'full_path' => [
                    'baz1' => '',
                    'baz2' => '',
                    'baz3' => '',
                ],
                'type' => [
                    'baz1' => '',
                    'baz2' => '',
                    'baz3' => '',
                ],
                'tmp_name' => [
                    'baz1' => '',
                    'baz2' => '',
                    'baz3' => '',
                ],
                'error' => [
                    'baz1' => 4,
                    'baz2' => 4,
                    'baz3' => 4,
                ],
                'size' => [
                    'baz1' => 0,
                    'baz2' => 0,
                    'baz3' => 0,
                ],
            ],
        ];
    }

    protected function complexFiles() : array
    {
        return [
            'foo' => [
                'name' => 'foo_name',
                'full_path' => 'foo_name',
                'type' => 'foo_type',
                'tmp_name' => 'foo_tmp_name',
                'error' => 4,
                'size' => 0,
            ],
            'dib' => [
                'name' => [
                    'dib1' => [
                        'dib1a' => 'dib1a_name',
                        'dib1b' => 'dib1b_name',
                        'dib1c' => 'dib1c_name',
                    ],
                    'dib2' => [
                        'dib2a' => 'dib2a_name',
                        'dib2b' => 'dib2b_name',
                        'dib2c' => 'dib2c_name',
                    ],
                    'dib3' => [
                        'dib3a' => 'dib3a_name',
                        'dib3b' => 'dib3b_name',
                        'dib3c' => 'dib3c_name',
                    ],
                ],
                'full_path' => [
                    'dib1' => [
                        'dib1a' => 'dib1a_name',
                        'dib1b' => 'dib1b_name',
                        'dib1c' => 'dib1c_name',
                    ],
                    'dib2' => [
                        'dib2a' => 'dib2a_name',
                        'dib2b' => 'dib2b_name',
                        'dib2c' => 'dib2c_name',
                    ],
                    'dib3' => [
                        'dib3a' => 'dib3a_name',
                        'dib3b' => 'dib3b_name',
                        'dib3c' => 'dib3c_name',
                    ],
                ],
                'type' => [
                    'dib1' => [
                        'dib1a' => 'dib1a_type',
                        'dib1b' => 'dib1b_type',
                        'dib1c' => 'dib1c_type',
                    ],
                    'dib2' => [
                        'dib2a' => 'dib2a_type',
                        'dib2b' => 'dib2b_type',
                        'dib2c' => 'dib2c_type',
                    ],
                    'dib3' => [
                        'dib3a' => 'dib3a_type',
                        'dib3b' => 'dib3b_type',
                        'dib3c' => 'dib3c_type',
                    ],
                ],
                'tmp_name' => [
                    'dib1' => [
                        'dib1a' => 'dib1a_tmp_name',
                        'dib1b' => 'dib1b_tmp_name',
                        'dib1c' => 'dib1c_tmp_name',
                    ],
                    'dib2' => [
                        'dib2a' => 'dib2a_tmp_name',
                        'dib2b' => 'dib2b_tmp_name',
                        'dib2c' => 'dib2c_tmp_name',
                    ],
                    'dib3' => [
                        'dib3a' => 'dib3a_tmp_name',
                        'dib3b' => 'dib3b_tmp_name',
                        'dib3c' => 'dib3c_tmp_name',
                    ],
                ],
                'error' => [
                    'dib1' => [
                        'dib1a' => 4,
                        'dib1b' => 4,
                        'dib1c' => 4,
                    ],
                    'dib2' => [
                        'dib2a' => 4,
                        'dib2b' => 4,
                        'dib2c' => 4,
                    ],
                    'dib3' => [
                        'dib3a' => 4,
                        'dib3b' => 4,
                        'dib3c' => 4,
                    ],
                ],
                'size' => [
                    'dib1' => [
                        'dib1a' => 0,
                        'dib1b' => 0,
                        'dib1c' => 0,
                    ],
                    'dib2' => [
                        'dib2a' => 0,
                        'dib2b' => 0,
                        'dib2c' => 0,
                    ],
                    'dib3' => [
                        'dib3a' => 0,
                        'dib3b' => 0,
                        'dib3c' => 0,
                    ],
                ],
            ],
        ];
    }
}