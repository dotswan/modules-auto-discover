<?php

use Dotswan\ModulesAutoDiscover\ModulesAutoDiscoverServiceProvider;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Laravel\Module;

beforeEach(function () {
    $this->modulePath = base_path('tests/temp/modules/TestModule');
});

afterEach(function () {
    // Clean up the temporary directories and files
    if (File::exists($this->modulePath)) {
        File::deleteDirectory(base_path('tests/temp/modules'));
    }

    $configCachePath = base_path('bootstrap/cache/config.php');
    if (File::exists($configCachePath)) {
        File::delete($configCachePath);
    }
});

it('loads configs when auto-discovery is enabled', function () {
    // Ensure bootstrap/cache/config.php does not exist
    File::delete(base_path('bootstrap/cache/config.php'));

    // Create a fake module directory
    File::makeDirectory($this->modulePath.'/Config', 0777, true);

    // Create a config file
    File::put($this->modulePath.'/Config/testconfig.php', "<?php\n return [ 'key' => 'value' ];");

    // Mock the module
    $moduleMock = Mockery::mock(Module::class);
    $moduleMock->shouldReceive('get')->with('auto-discovery')->andReturn(true);
    $moduleMock->shouldReceive('getPath')->andReturn($this->modulePath);
    $moduleMock->shouldReceive('getLowerName')->andReturn('testmodule');

    // Mock the 'modules' binding
    $modulesMock = Mockery::mock();
    $modulesMock->shouldReceive('allEnabled')->andReturn([$moduleMock]);
    $this->app->instance('modules', $modulesMock);

    // Reload the service provider
    $this->app->register(ModulesAutoDiscoverServiceProvider::class);

    // Assert config is loaded
    expect(config('testconfig.key'))->toBe('value');
});

it('does not load configs when auto-discovery is disabled', function () {
    // Ensure bootstrap/cache/config.php does not exist
    File::delete(base_path('bootstrap/cache/config.php'));

    // Create a fake module directory
    File::makeDirectory($this->modulePath.'/Config', 0777, true);

    // Create a config file
    File::put($this->modulePath.'/Config/testconfig.php', "<?php\n return [ 'key' => 'value' ];");

    // Mock the module
    $moduleMock = Mockery::mock(Module::class);
    $moduleMock->shouldReceive('get')->with('auto-discovery')->andReturn(false);
    $moduleMock->shouldNotReceive('getPath');
    $moduleMock->shouldNotReceive('getLowerName');

    // Mock the 'modules' binding
    $modulesMock = Mockery::mock();
    $modulesMock->shouldReceive('allEnabled')->andReturn([$moduleMock]);
    $this->app->instance('modules', $modulesMock);

    // Reload the service provider
    $this->app->register(ModulesAutoDiscoverServiceProvider::class);

    // Assert config is not loaded
    expect(config('testconfig.key'))->toBeNull();
});

it('loads translations correctly', function () {
    // Create a fake module directory
    File::makeDirectory($this->modulePath.'/Lang/en', 0777, true);

    // Create translation files
    File::put($this->modulePath.'/Lang/en/messages.php', "<?php\n return [ 'welcome' => 'Welcome from module' ];");
    File::put($this->modulePath.'/Lang/en.json', json_encode(['Hello' => 'Hello from module']));

    // Mock the module
    $moduleMock = Mockery::mock(Module::class);
    $moduleMock->shouldReceive('get')->with('auto-discovery')->andReturn(true);
    $moduleMock->shouldReceive('getPath')->andReturn($this->modulePath);
    $moduleMock->shouldReceive('getLowerName')->andReturn('testmodule');

    // Mock the 'modules' binding
    $modulesMock = Mockery::mock();
    $modulesMock->shouldReceive('allEnabled')->andReturn([$moduleMock]);
    $this->app->instance('modules', $modulesMock);

    // Reload the service provider
    $this->app->register(ModulesAutoDiscoverServiceProvider::class);

    // Assert translations are loaded
    expect(trans('testmodule::messages.welcome'))->toBe('Welcome from module');
    expect(trans('Hello'))->toBe('Hello from module');
});

it('does not load configs when config cache exists', function () {
    // Create 'bootstrap/cache/config.php' file
    $configCachePath = base_path('bootstrap/cache/config.php');

    // Ensure the directory exists and suppress errors if it already exists
    File::makeDirectory(dirname($configCachePath), 0777, true, true);

    File::put($configCachePath, '<?php return [];');

    // Create a fake module directory
    File::makeDirectory($this->modulePath.'/Config', 0777, true);

    // Create a config file
    File::put($this->modulePath.'/Config/testconfig.php', "<?php\n return [ 'key' => 'value' ];");

    // Mock the module
    $moduleMock = Mockery::mock(Module::class);
    $moduleMock->shouldReceive('get')->with('auto-discovery')->andReturn(true);
    $moduleMock->shouldReceive('getPath')->andReturn($this->modulePath);
    $moduleMock->shouldReceive('getLowerName')->andReturn('testmodule');

    // Mock the 'modules' binding
    $modulesMock = Mockery::mock();
    $modulesMock->shouldReceive('allEnabled')->andReturn([$moduleMock]);
    $this->app->instance('modules', $modulesMock);

    // Reload the service provider
    $this->app->register(ModulesAutoDiscoverServiceProvider::class);

    // Assert config is not loaded
    expect(config('testconfig.key'))->toBeNull();
});
