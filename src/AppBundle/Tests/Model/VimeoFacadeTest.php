<?php

namespace AppBundle\Tests\Model;
use AppBundle\Model\VimeoFacade;

/**
 * Class VimeoFacadeTest
 * @package AppBundle\Tests\Model
 */
class VimeoFacadeTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testVideoUrl()
    {
        $id = 1;

        $vimeo = $this->getVimeoMock();
        $vimeo->expects($this->once())
          ->method('request')
          ->with('/videos/' . $id)
          ->willReturn($this->vimeoRealResponse);

        $vimeoFacade = new VimeoFacade($vimeo);
        $result = $vimeoFacade->getVideoUrl($id);
        $this->assertSame($result, $this->expectedResult);
    }

    /**
     * @expectedException \AppBundle\Exception\VimeoException
     */
    public function testVideoNotFound()
    {
        $id = 1;

        $vimeo = $this->getVimeoMock();
        $vimeo->expects($this->once())
          ->method('request')
          ->with('/videos/' . $id)
          ->willReturn(['status' => 404]);

        $vimeoFacade = new VimeoFacade($vimeo);
        $vimeoFacade->getVideoUrl($id);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getVimeoMock()
    {
        $mock = $this->getMockBuilder('Vimeo\Vimeo')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    private $expectedResult = [
      'hd' => 'https://player.vimeo.com/external/133649696.hd.mp4?s=cf538be14cf1d4d6c2c5c07c482189ed&profile_id=113&oauth2_token_id=74793831',
      'sd' => 'https://player.vimeo.com/external/133649696.m3u8?p=mobile,standard,high&s=63b7280ed8238bbf5bfcaf0172d00fe8&oauth2_token_id=74793831',
    ];

    private $vimeoRealResponse = array (
        'body' =>
          array (
          'uri' => '/videos/133649696',
          'name' => 'Izrael 3.',
          'description' => null,
          'link' => 'https://vimeo.com/133649696',
          'duration' => 49,
          'width' => 1920,
          'language' => null,
          'height' => 1080,
          'embed' =>
            array (
              'uri' => null,
              'html' => '<iframe src="https://player.vimeo.com/video/133649696?title=0&byline=0&portrait=0&badge=0&autopause=0&player_id=0" width="1920" height="1080" frameborder="0" title="Izrael 3." webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
              'buttons' =>
                array (
                  'like' => true,
                  'watchlater' => true,
                  'share' => true,
                  'embed' => true,
                  'hd' => false,
                  'fullscreen' => true,
                  'scaling' => true,
                ),
                'logos' =>
                array (
                  'vimeo' => true,
                  'custom' =>
                    array (
                      'active' => false,
                      'link' => null,
                      'sticky' => false,
                    ),
                ),
                'title' =>
                array (
                  'name' => 'user',
                  'owner' => 'user',
                  'portrait' => 'user',
                ),
                'playbar' => true,
                'volume' => true,
                'color' => '00adef',
            ),
            'created_time' => '2015-07-16T12:07:05+00:00',
            'modified_time' => '2015-07-16T13:17:24+00:00',
            'content_rating' =>
            array (
              0 => 'unrated',
            ),
            'license' => null,
            'privacy' =>
            array (
              'view' => 'disable',
              'embed' => 'whitelist',
              'download' => false,
              'add' => false,
              'comments' => 'nobody',
            ),
            'pictures' =>
            array (
              'uri' => '/videos/133649696/pictures/526798673',
              'active' => true,
              'sizes' =>
                array (
                    0 =>
                    array (
                      'width' => 100,
                      'height' => 75,
                      'link' => 'https://i.vimeocdn.com/video/526798673_100x75.jpg',
                    ),
                    1 =>
                    array (
                      'width' => 200,
                      'height' => 150,
                      'link' => 'https://i.vimeocdn.com/video/526798673_200x150.jpg',
                    ),
                    2 =>
                    array (
                      'width' => 295,
                      'height' => 166,
                      'link' => 'https://i.vimeocdn.com/video/526798673_295x166.jpg',
                    ),
                    3 =>
                    array (
                      'width' => 640,
                      'height' => 360,
                      'link' => 'https://i.vimeocdn.com/video/526798673_640x360.jpg',
                    ),
                    4 =>
                    array (
                      'width' => 960,
                      'height' => 540,
                      'link' => 'https://i.vimeocdn.com/video/526798673_960x540.jpg',
                    ),
                    5 =>
                    array (
                      'width' => 1280,
                      'height' => 720,
                      'link' => 'https://i.vimeocdn.com/video/526798673_1280x720.jpg',
                    ),
                ),
            ),
            'tags' =>
            array (
            ),
            'stats' =>
            array (
              'plays' => 8,
            ),
            'metadata' =>
            array (
              'connections' =>
                array (
                  'comments' =>
                    array (
                      'uri' => '/videos/133649696/comments',
                      'options' =>
                        array (
                          0 => 'GET',
                          1 => 'POST',
                        ),
                        'total' => 0,
                    ),
                    'credits' =>
                    array (
                      'uri' => '/videos/133649696/credits',
                      'options' =>
                        array (
                          0 => 'GET',
                          1 => 'POST',
                        ),
                        'total' => 1,
                    ),
                    'likes' =>
                    array (
                      'uri' => '/videos/133649696/likes',
                      'options' =>
                        array (
                          0 => 'GET',
                        ),
                        'total' => 0,
                    ),
                    'pictures' =>
                    array (
                      'uri' => '/videos/133649696/pictures',
                      'options' =>
                        array (
                          0 => 'GET',
                          1 => 'POST',
                        ),
                        'total' => 1,
                    ),
                    'texttracks' =>
                    array (
                      'uri' => '/videos/133649696/texttracks',
                      'options' =>
                        array (
                          0 => 'GET',
                          1 => 'POST',
                        ),
                        'total' => 0,
                    ),
                ),
                'interactions' =>
                array (
                  'watchlater' =>
                    array (
                      'added' => false,
                      'added_time' => null,
                      'uri' => '/users/42024728/watchlater/133649696',
                    ),
                ),
            ),
            'user' =>
            array (
              'uri' => '/users/42024728',
              'name' => 'Jakub Hladik',
              'link' => 'https://vimeo.com/user42024728',
              'location' => null,
              'bio' => null,
              'created_time' => '2015-07-15T09:35:52+00:00',
              'account' => 'pro',
              'pictures' => null,
              'websites' =>
                array (
                ),
                'metadata' =>
                array (
                  'connections' =>
                    array (
                      'activities' =>
                        array (
                          'uri' => '/users/42024728/activities',
                          'options' =>
                            array (
                              0 => 'GET',
                            ),
                        ),
                        'albums' =>
                        array (
                          'uri' => '/users/42024728/albums',
                          'options' =>
                            array (
                              0 => 'GET',
                            ),
                            'total' => 0,
                        ),
                        'channels' =>
                        array (
                          'uri' => '/users/42024728/channels',
                          'options' =>
                            array (
                              0 => 'GET',
                            ),
                            'total' => 0,
                        ),
                        'feed' =>
                        array (
                          'uri' => '/users/42024728/feed',
                          'options' =>
                            array (
                              0 => 'GET',
                            ),
                        ),
                        'followers' =>
                        array (
                          'uri' => '/users/42024728/followers',
                          'options' =>
                            array (
                              0 => 'GET',
                            ),
                            'total' => 0,
                        ),
                        'following' =>
                        array (
                          'uri' => '/users/42024728/following',
                          'options' =>
                            array (
                              0 => 'GET',
                            ),
                            'total' => 0,
                        ),
                        'groups' =>
                        array (
                          'uri' => '/users/42024728/groups',
                          'options' =>
                            array (
                              0 => 'GET',
                            ),
                            'total' => 0,
                        ),
                        'likes' =>
                        array (
                          'uri' => '/users/42024728/likes',
                          'options' =>
                            array (
                              0 => 'GET',
                            ),
                            'total' => 0,
                        ),
                        'portfolios' =>
                        array (
                          'uri' => '/users/42024728/portfolios',
                          'options' =>
                            array (
                              0 => 'GET',
                            ),
                            'total' => 0,
                        ),
                        'videos' =>
                        array (
                          'uri' => '/users/42024728/videos',
                          'options' =>
                            array (
                              0 => 'GET',
                            ),
                            'total' => 2,
                        ),
                        'watchlater' =>
                        array (
                          'uri' => '/users/42024728/watchlater',
                          'options' =>
                            array (
                              0 => 'GET',
                            ),
                            'total' => 0,
                        ),
                        'shared' =>
                        array (
                          'uri' => '/users/42024728/shared/videos',
                          'options' =>
                            array (
                              0 => 'GET',
                            ),
                            'total' => 0,
                        ),
                        'pictures' =>
                        array (
                          'uri' => '/users/42024728/pictures',
                          'options' =>
                            array (
                              0 => 'GET',
                              1 => 'POST',
                            ),
                            'total' => 0,
                        ),
                    ),
                ),
                'preferences' =>
                array (
                  'videos' =>
                    array (
                      'privacy' => 'anybody',
                    ),
                ),
                'content_filter' =>
                array (
                  0 => 'language',
                  1 => 'drugs',
                  2 => 'violence',
                  3 => 'nudity',
                  4 => 'safe',
                  5 => 'unrated',
                ),
            ),
            'download' =>
            array (
              0 =>
                array (
                  'quality' => 'hd',
                  'type' => 'video/mp4',
                  'width' => 1920,
                  'height' => 1080,
                  'expires' => '2015-07-16T16:18:40+00:00',
                  'link' => 'https://vimeo.com/api/file/download?clip_id=133649696&id=74793831&profile=119&codec=H264&exp=1437063520&sig=dafdfa594ea4c706b1180584ddc2a9d0d29ba738',
                  'created_time' => '2015-07-16T12:16:44+00:00',
                  'size' => 28502060,
                  'md5' => '49158f802af5958e61976b741587cb9e',
                ),
                1 =>
                array (
                  'quality' => 'hd',
                  'type' => 'video/mp4',
                  'width' => 1280,
                  'height' => 720,
                  'expires' => '2015-07-16T16:18:40+00:00',
                  'link' => 'https://vimeo.com/api/file/download?clip_id=133649696&id=74793831&profile=113&codec=H264&exp=1437063520&sig=7ab1d6835f8e287c12d4dcec61895a2bf7889728',
                  'created_time' => '2015-07-16T12:16:44+00:00',
                  'size' => 15949748,
                  'md5' => '1748eb7ea45f72f6fb0b5d93a5405294',
                ),
                2 =>
                array (
                  'quality' => 'sd',
                  'type' => 'video/mp4',
                  'width' => 640,
                  'height' => 360,
                  'expires' => '2015-07-16T16:18:40+00:00',
                  'link' => 'https://vimeo.com/api/file/download?clip_id=133649696&id=74793831&profile=112&codec=H264&exp=1437063520&sig=da0b87f3b38df001b9dcb61ba88494ae7cbffa63',
                  'created_time' => '2015-07-16T12:16:44+00:00',
                  'size' => 5122247,
                  'md5' => '4b8167bed19655926f68e36b8757726a',
                ),
                3 =>
                array (
                  'quality' => 'source',
                  'type' => 'source',
                  'width' => 1440,
                  'height' => 1080,
                  'expires' => '2015-07-16T16:18:40+00:00',
                  'link' => 'https://vimeo.com/api/file/download?clip_id=133649696&id=74793831&profile=source&codec=source&exp=1437063520&sig=d8d1d548c340891c61b5d7c6edbc5cc13c32883d',
                  'created_time' => '2015-07-16T12:07:05+00:00',
                  'size' => 51630355,
                  'md5' => '79c8432c1040c16552a330c9e90a67eb',
                ),
            ),
            'files' =>
            array (
              0 =>
                array (
                  'quality' => 'hd',
                  'type' => 'video/mp4',
                  'width' => 1920,
                  'height' => 1080,
                  'link' => 'http://player.vimeo.com/external/133649696.hd.mp4?s=cf538be14cf1d4d6c2c5c07c482189ed&profile_id=119&oauth2_token_id=74793831',
                  'link_secure' => 'https://player.vimeo.com/external/133649696.hd.mp4?s=cf538be14cf1d4d6c2c5c07c482189ed&profile_id=119&oauth2_token_id=74793831',
                  'created_time' => '2015-07-16T12:16:44+00:00',
                  'size' => 28502060,
                  'md5' => '49158f802af5958e61976b741587cb9e',
                ),
                1 =>
                array (
                  'quality' => 'hd',
                  'type' => 'video/mp4',
                  'width' => 1280,
                  'height' => 720,
                  'link' => 'http://player.vimeo.com/external/133649696.hd.mp4?s=cf538be14cf1d4d6c2c5c07c482189ed&profile_id=113&oauth2_token_id=74793831',
                  'link_secure' => 'https://player.vimeo.com/external/133649696.hd.mp4?s=cf538be14cf1d4d6c2c5c07c482189ed&profile_id=113&oauth2_token_id=74793831',
                  'created_time' => '2015-07-16T12:16:44+00:00',
                  'size' => 15949748,
                  'md5' => '1748eb7ea45f72f6fb0b5d93a5405294',
                ),
                2 =>
                array (
                  'quality' => 'sd',
                  'type' => 'video/mp4',
                  'width' => 640,
                  'height' => 360,
                  'link' => 'http://player.vimeo.com/external/133649696.sd.mp4?s=4c009b381178cc9354ba0ab13681af05&profile_id=112&oauth2_token_id=74793831',
                  'link_secure' => 'https://player.vimeo.com/external/133649696.sd.mp4?s=4c009b381178cc9354ba0ab13681af05&profile_id=112&oauth2_token_id=74793831',
                  'created_time' => '2015-07-16T12:16:44+00:00',
                  'size' => 5122247,
                  'md5' => '4b8167bed19655926f68e36b8757726a',
                ),
                3 =>
                array (
                  'quality' => 'hls',
                  'type' => 'video/mp4',
                  'link' => 'https://player.vimeo.com/external/133649696.m3u8?p=mobile,standard,high&s=63b7280ed8238bbf5bfcaf0172d00fe8&oauth2_token_id=74793831',
                  'link_secure' => 'https://player.vimeo.com/external/133649696.m3u8?p=mobile,standard,high&s=63b7280ed8238bbf5bfcaf0172d00fe8&oauth2_token_id=74793831',
                  'created_time' => '2015-07-16T12:16:44+00:00',
                  'size' => 28502060,
                  'md5' => '49158f802af5958e61976b741587cb9e',
                ),
            ),
            'app' => null,
            'status' => 'available',
            'embed_presets' => null,
          ),
          'status' => 200,
          'headers' =>
              array (
                  'Server' => 'nginx',
                  'Content-Type' => 'application/vnd.vimeo.video+json',
                  'Cache-Control' => 'no-cache, max-age=315360000',
                  'Strict-Transport-Security' => 'max-age=900; includeSubDomains',
                  'Expires' => 'Sun, 13 Jul 2025 13',
                  'Content-Length' => '6695',
                  'Accept-Ranges' => 'bytes',
                  'Date' => 'Thu, 16 Jul 2015 13',
                  'Via' => '1.1 varnish',
                  'Age' => '0',
                  'Connection' => 'keep-alive',
                  'Set-Cookie' => 'clip_v=1; expires=Mon, 14 Sep 2015 13',
                  'X-Served-By' => 'cache-fra1244-FRA',
                  'X-Cache' => 'MISS',
                  'X-Cache-Hits' => '0',
                  'X-Timer' => 'S1437052719.926021,VS0,VE238',
                  'Vary' => 'Accept,Vimeo-Client-Id,Accept-Encoding',
              ),
    );
}
