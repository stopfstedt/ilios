<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\DTO\LearningMaterialDTO;
use App\Service\LearningMaterialTextExtractor;
use App\Service\NonCachingIliosFileSystem;
use App\Service\TemporaryFileSystem;
use Exception;
use Mockery as m;
use App\Service\IliosFileSystem;
use App\Tests\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Vaites\ApacheTika\Client;

class LearningMaterialTextExtractorTest extends TestCase
{
    public const string TEST_FILE_PATH = __DIR__ . '/FakeTestFiles/LearningMaterialTextExtractor_TEST.txt';
    private LearningMaterialTextExtractor $extractor;
    private NonCachingIliosFileSystem|m\MockInterface $nonCachingFileSystem;
    private IliosFileSystem|m\MockInterface $fileSystem;
    private TemporaryFileSystem|m\MockInterface $temporaryFileSystem;
    private Client|m\MockInterface $tikaClient;

    public function setUp(): void
    {
        parent::setUp();
        $this->nonCachingFileSystem = m::mock(NonCachingIliosFileSystem::class);
        $this->temporaryFileSystem = m::mock(TemporaryFileSystem::class);
        $this->fileSystem = m::mock(IliosFileSystem::class);
        $this->tikaClient = m::mock(Client::class);
        $this->extractor = new LearningMaterialTextExtractor(
            $this->nonCachingFileSystem,
            $this->temporaryFileSystem,
            $this->fileSystem,
            $this->tikaClient,
        );
        $fs = new Filesystem();
        if (!$fs->exists(dirname(self::TEST_FILE_PATH))) {
            $fs->mkdir(dirname(self::TEST_FILE_PATH));
        }
        $fs->copy(__FILE__, self::TEST_FILE_PATH);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->nonCachingFileSystem);
        unset($this->temporaryFileSystem);
        unset($this->fileSystem);
        unset($this->tikaClient);
        unset($this->extractor);
    }

    public function testDisabled(): void
    {
        self::expectNotToPerformAssertions();
        $extractor = new LearningMaterialTextExtractor(
            $this->nonCachingFileSystem,
            $this->temporaryFileSystem,
            $this->fileSystem,
            null,
        );
        $dto = m::mock(LearningMaterialDTO::class);
        $extractor->extract($dto);
    }

    public function testExtract(): void
    {
        $dto = m::mock(LearningMaterialDTO::class);
        $dto->relativePath = 'dir/lm/24/24jj';
        $dto->filename = 'jayden.pdf';
        $dto->mimetype = 'test/pdf';
        $this->nonCachingFileSystem
            ->shouldReceive('checkLearningMaterialRelativePath')
            ->with($dto->relativePath)
            ->once()
            ->andReturn(true);
        $this->nonCachingFileSystem
            ->shouldReceive('getFileContents')
            ->with($dto->relativePath)
            ->once()
            ->andReturn('lm-contents');
        $tmpFile = new File(self::TEST_FILE_PATH);
        $this->temporaryFileSystem
            ->shouldReceive('createFile')
            ->with('lm-contents')
            ->once()
            ->andReturn($tmpFile);
        $this->tikaClient
            ->shouldReceive('getText')
            ->once()
            ->with(self::TEST_FILE_PATH)
            ->andReturn('lm-text');
        $this->tikaClient
            ->shouldReceive('isMimeTypeSupported')
            ->once()
            ->with('test/pdf')
            ->andReturn(true);

        $this->fileSystem
            ->shouldReceive('checkIfLearningMaterialTextFileExists')
            ->once()
            ->with($dto->relativePath)
            ->andReturn(false);
        $this->fileSystem
            ->shouldReceive('storeLearningMaterialText')
            ->once()
            ->with($dto->relativePath, 'lm-text')
            ->andReturn('lm-text-path');
        $this->assertTrue(file_exists(self::TEST_FILE_PATH));
        $this->extractor->extract($dto);
        $this->assertFalse(file_exists(self::TEST_FILE_PATH));
    }

    public function testUnsupportedFileType(): void
    {
        self::expectNotToPerformAssertions();
        $dto = m::mock(LearningMaterialDTO::class);
        $dto->filename = null;
        $this->extractor->extract($dto);
    }

    public function testNonFileLm(): void
    {
        $dto = m::mock(LearningMaterialDTO::class);
        $dto->filename = 't.txt';
        $dto->mimetype = 'test/pdf';
        $this->tikaClient
            ->shouldReceive('isMimeTypeSupported')
            ->once()
            ->with('test/pdf')
            ->andReturn(false);
        $this->extractor->extract($dto);
    }

    public function testFileAlreadyExists(): void
    {
        $dto = m::mock(LearningMaterialDTO::class);
        $dto->relativePath = 'dir/lm/24/24jj';
        $dto->filename = 'jayden.pdf';
        $dto->mimetype = 'test/pdf';
        $this->tikaClient
            ->shouldReceive('isMimeTypeSupported')
            ->once()
            ->with('test/pdf')
            ->andReturn(true);

        $this->fileSystem
            ->shouldReceive('checkIfLearningMaterialTextFileExists')
            ->once()
            ->with($dto->relativePath)
            ->andReturn(true);
        $this->extractor->extract($dto);
    }



    public function testMissingFileThrowsException(): void
    {
        self::expectException(Exception::class);
        $dto = m::mock(LearningMaterialDTO::class);
        $dto->relativePath = 'dir/lm/24/24jj';
        $dto->filename = 'jayden.pdf';
        $dto->mimetype = 'test/pdf';
        $this->nonCachingFileSystem
            ->shouldReceive('checkLearningMaterialRelativePath')
            ->with($dto->relativePath)
            ->once()
            ->andReturn(false);
        $tmpFile = new File(self::TEST_FILE_PATH);
        $this->tikaClient
            ->shouldReceive('isMimeTypeSupported')
            ->once()
            ->with('test/pdf')
            ->andReturn(true);

        $this->fileSystem
            ->shouldReceive('checkIfLearningMaterialTextFileExists')
            ->once()
            ->with($dto->relativePath)
            ->andReturn(false);
        $this->extractor->extract($dto);
    }



    public function testCatchTikaExtractionProblem(): void
    {
        $dto = m::mock(LearningMaterialDTO::class);
        $dto->relativePath = 'dir/lm/24/24jj';
        $dto->filename = 'jayden.pdf';
        $dto->mimetype = 'test/pdf';
        $this->nonCachingFileSystem
            ->shouldReceive('checkLearningMaterialRelativePath')
            ->with($dto->relativePath)
            ->once()
            ->andReturn(true);
        $this->nonCachingFileSystem
            ->shouldReceive('getFileContents')
            ->with($dto->relativePath)
            ->once()
            ->andReturn('lm-contents');
        $tmpFile = new File(self::TEST_FILE_PATH);
        $this->temporaryFileSystem
            ->shouldReceive('createFile')
            ->with('lm-contents')
            ->once()
            ->andReturn($tmpFile);
        $this->tikaClient
            ->shouldReceive('getText')
            ->once()
            ->with(self::TEST_FILE_PATH)
            ->andThrow(Exception::class, 'Unprocessable document', 422);
        $this->tikaClient
            ->shouldReceive('isMimeTypeSupported')
            ->once()
            ->with('test/pdf')
            ->andReturn(true);

        $this->fileSystem
            ->shouldReceive('checkIfLearningMaterialTextFileExists')
            ->once()
            ->with($dto->relativePath)
            ->andReturn(false);
        $this->fileSystem
            ->shouldReceive('storeLearningMaterialText')
            ->once()
            ->with($dto->relativePath, 'jayden.pdf')
            ->andReturn('lm-text-path');
        $this->assertTrue(file_exists(self::TEST_FILE_PATH));
        $this->extractor->extract($dto);
        $this->assertFalse(file_exists(self::TEST_FILE_PATH));
    }
}
