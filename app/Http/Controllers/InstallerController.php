<?php

namespace App\Http\Controllers;

use App\Models\AvailableWebsite;
use App\Models\Template;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InstallerController extends Controller
{
    public function showStepOne(): View|RedirectResponse
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        return view('home');
    }

    public function showStepTwo(): View|RedirectResponse
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        if (! session()->has('installer.step_one')) {
            return redirect('/installer');
        }

        return view('home');
    }

    public function showStepThree(): View|RedirectResponse
    {
        if ($this->isInstalled()) {
            return redirect('/');
        }

        if (! session()->has('installer.step_one')) {
            return redirect('/installer');
        }

        if (! session()->has('installer.step_two')) {
            return redirect('/installer/theme');
        }

        return view('home');
    }

    public function installationStatus(): JsonResponse
    {
        $payload = $this->getInstallationPayload();
        $installed = is_array($payload) && ($payload['installed'] ?? false) === true;

        $businessCategory = $payload['step_one']['business_category'] ?? null;
        $themeId = $payload['step_two']['theme_id'] ?? null;
        $redirectTo = null;

        if ($installed) {
            $redirectTo = url('/').'?business_category='.$businessCategory.'&theme_id='.$themeId;
        }

        return response()->json([
            'installed' => $installed,
            'redirect_to' => $redirectTo,
            'business_category' => $businessCategory,
            'theme_id' => $themeId,
        ]);
    }

    public function getBusinessCategories(): JsonResponse
    {
        if ($response = $this->installerLockedResponse()) {
            return $response;
        }

        return response()->json(
            AvailableWebsite::where('is_active', 1)
                ->select('id', 'name')
                ->get()
        );
    }

    public function storeStepOne(Request $request): JsonResponse
    {
        if ($response = $this->installerLockedResponse()) {
            return $response;
        }

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'business_name' => 'required',
            'business_url' => 'required',
            'business_category' => 'required',
        ]);

        session([
            'installer.step_one' => $validated,
        ]);

        session()->forget('installer.step_two');
        session()->forget('installer.step_three');

        return response()->json([
            'status' => true,
            'message' => 'Step 1 saved successfully',
        ]);
    }

    public function debugStepOne(): JsonResponse
    {
        if ($response = $this->installerLockedResponse()) {
            return $response;
        }

        return response()->json([
            'session_data' => session('installer.step_one'),
        ]);
    }

    public function getThemes(): JsonResponse
    {
        if ($response = $this->installerLockedResponse()) {
            return $response;
        }

        $stepOne = session('installer.step_one');

        if (! $stepOne) {
            return response()->json([
                'status' => false,
                'message' => 'Step one data not found in session.',
            ], 422);
        }

        $themes = Template::query()
            ->where('is_active', true)
            ->where('available_website_id', $stepOne['business_category'])
            ->orderBy('order')
            ->select('id', 'name', 'slug', 'preview_image', 'available_website_id')
            ->get();

        return response()->json([
            'status' => true,
            'themes' => $themes,
            'business_category' => $stepOne['business_category'],
        ]);
    }

    public function storeStepTwo(Request $request): JsonResponse
    {
        if ($response = $this->installerLockedResponse()) {
            return $response;
        }

        $stepOne = session('installer.step_one');

        if (! $stepOne) {
            return response()->json([
                'status' => false,
                'message' => 'Step one data not found in session.',
            ], 422);
        }

        $validated = $request->validate([
            'theme_id' => [
                'required',
                Rule::exists('templates', 'id')->where(function ($query) use ($stepOne) {
                    $query->where('available_website_id', $stepOne['business_category'])
                        ->where('is_active', true);
                }),
            ],
        ]);

        session([
            'installer.step_two' => [
                'theme_id' => (int) $validated['theme_id'],
            ],
        ]);

        session()->forget('installer.step_three');

        return response()->json([
            'status' => true,
            'message' => 'Theme selected successfully.',
            'data' => session('installer.step_two'),
        ]);
    }

    public function getStepThreeData(): JsonResponse
    {
        if ($response = $this->installerLockedResponse()) {
            return $response;
        }

        $stepOne = session('installer.step_one');
        $stepTwo = session('installer.step_two');
        $stepThree = session('installer.step_three', []);

        if (! $stepOne) {
            return response()->json([
                'status' => false,
                'message' => 'Step one data not found in session.',
            ], 422);
        }

        if (! $stepTwo) {
            return response()->json([
                'status' => false,
                'message' => 'Step two data not found in session.',
            ], 422);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'app_name' => $stepThree['app_name'] ?? $stepOne['business_name'],
                'website_url' => $stepThree['website_url'] ?? $stepOne['business_url'],
                'db_database' => $stepThree['db_database'] ?? '',
                'db_username' => $stepThree['db_username'] ?? '',
                'db_password' => $stepThree['db_password'] ?? '',
            ],
        ]);
    }

    public function storeStepThree(Request $request): JsonResponse
    {
        if ($response = $this->installerLockedResponse()) {
            return $response;
        }

        if (! session()->has('installer.step_one')) {
            return response()->json([
                'status' => false,
                'message' => 'Step one data not found in session.',
            ], 422);
        }

        if (! session()->has('installer.step_two')) {
            return response()->json([
                'status' => false,
                'message' => 'Step two data not found in session.',
            ], 422);
        }

        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'website_url' => 'required|string|max:255',
            'db_database' => 'required|string|max:255',
            'db_username' => 'required|string|max:255',
            'db_password' => 'nullable|string|max:255',
        ]);

        session([
            'installer.step_three' => $validated,
        ]);

        try {
            $this->updateEnvironmentFile([
                'APP_NAME' => $validated['app_name'],
                'APP_URL' => $validated['website_url'],
                'DB_DATABASE' => $validated['db_database'],
                'DB_USERNAME' => $validated['db_username'],
                'DB_PASSWORD' => $validated['db_password'] ?? '',
            ]);

            $this->writeInstallationFile([
                'installed' => true,
                'installed_at' => now()->toIso8601String(),
                'step_one' => session('installer.step_one'),
                'step_two' => session('installer.step_two'),
                'step_three' => [
                    'app_name' => $validated['app_name'],
                    'website_url' => $validated['website_url'],
                    'db_database' => $validated['db_database'],
                    'db_username' => $validated['db_username'],
                ],
            ]);

            Artisan::call('config:clear');
            session()->forget('installer.step_one');
            session()->forget('installer.step_two');
            session()->forget('installer.step_three');
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Installation completed successfully.',
            'data' => [
                'redirect_to' => url('/').'?business_category='.session('installer.step_one.business_category').'&theme_id='.session('installer.step_two.theme_id'),
            ],
        ]);
    }

    private function updateEnvironmentFile(array $values): void
    {
        $environmentPath = base_path('.env');

        if (! is_file($environmentPath) || ! is_readable($environmentPath) || ! is_writable($environmentPath)) {
            throw new \RuntimeException('The environment file is not accessible for writing.');
        }

        $content = file_get_contents($environmentPath);

        if ($content === false) {
            throw new \RuntimeException('Unable to read the environment file.');
        }

        foreach ($values as $key => $value) {
            $formattedValue = $this->formatEnvironmentValue($value);
            $pattern = '/^'.preg_quote($key, '/').'=.*$/m';
            $replacement = "{$key}={$formattedValue}";

            if (preg_match($pattern, $content) === 1) {
                $content = preg_replace($pattern, $replacement, $content, 1) ?? $content;
                continue;
            }

            $content .= PHP_EOL.$replacement;
        }

        if (file_put_contents($environmentPath, $content) === false) {
            throw new \RuntimeException('Unable to write to the environment file.');
        }
    }

    private function writeInstallationFile(array $payload): void
    {
        $installationFile = $this->installationFilePath();
        $directory = dirname($installationFile);

        if (! is_dir($directory)) {
            File::ensureDirectoryExists($directory);
        }

        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        if ($json === false || file_put_contents($installationFile, $json) === false) {
            throw new \RuntimeException('Unable to write the installation marker file.');
        }
    }

    private function installerLockedResponse(): ?JsonResponse
    {
        $payload = $this->getInstallationPayload();

        if (! is_array($payload) || ($payload['installed'] ?? false) !== true) {
            return null;
        }

        $businessCategory = $payload['step_one']['business_category'] ?? '';
        $themeId = $payload['step_two']['theme_id'] ?? '';

        return response()->json([
            'status' => false,
            'message' => 'The application has already been installed.',
            'installed' => true,
            'redirect_to' => url('/').'?business_category='.$businessCategory.'&theme_id='.$themeId,
        ], 403);
    }

    private function isInstalled(): bool
    {
        $payload = $this->getInstallationPayload();

        return is_array($payload) && ($payload['installed'] ?? false) === true;
    }

    private function getInstallationPayload(): ?array
    {
        if (! is_file($this->installationFilePath())) {
            return null;
        }

        $payload = json_decode((string) file_get_contents($this->installationFilePath()), true);

        return is_array($payload) ? $payload : null;
    }

    private function installationFilePath(): string
    {
        return storage_path('app/installer/installed.json');
    }

    private function formatEnvironmentValue(?string $value): string
    {
        $value = (string) ($value ?? '');

        if ($value === '') {
            return '';
        }

        if (preg_match("/\\s|#|=|\"|'|\\\\/", $value) === 1) {
            return '"'.addcslashes($value, "\\\"").'"';
        }

        return $value;
    }
}
