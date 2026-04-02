# Identity Module Reference

The Identity module handles authentication, user management, roles, permissions, multi-tenancy, companies, branches, profiles, sessions, and API keys.

## Client Access

```php
$essabu = new EssabuClient('your-api-key');
$identity = $essabu->identity;
```

## Available API Classes

| Class | Accessor | Description |
|-------|----------|-------------|
| `AuthApi` | `$identity->auth` | Authentication, password recovery |
| `UserApi` | `$identity->users` | User management |
| `RoleApi` | `$identity->roles` | Role management |
| `PermissionApi` | `$identity->permissions` | Permission management |
| `TenantApi` | `$identity->tenants` | Tenant management |
| `CompanyApi` | `$identity->companies` | Company management |
| `BranchApi` | `$identity->branches` | Branch management |
| `ProfileApi` | `$identity->profile` | User profile operations |
| `SessionApi` | `$identity->sessions` | Session management |
| `ApiKeyApi` | `$identity->apiKeys` | API key management |

---

## AuthApi

Base path: `identity/auth`

| Method | Endpoint | Description |
|--------|----------|-------------|
| `login(array $credentials) -> array` | `POST /api/identity/auth/login` | Authenticate |
| `logout() -> array` | `POST /api/identity/auth/logout` | Logout |
| `refresh(string $refreshToken) -> array` | `POST /api/identity/auth/refresh` | Refresh token |
| `forgotPassword(string $email) -> array` | `POST /api/identity/auth/forgot-password` | Request reset |
| `resetPassword(array $data) -> array` | `POST /api/identity/auth/reset-password` | Reset password |
| `verifyEmail(string $token) -> array` | `POST /api/identity/auth/verify-email` | Verify email |

```php
// Login
$tokens = $identity->auth->login(['email' => 'user@example.com', 'password' => 'secret']);

// Refresh token
$newTokens = $identity->auth->refresh($refreshToken);

// Password recovery
$identity->auth->forgotPassword('user@example.com');
$identity->auth->resetPassword(['token' => $token, 'password' => 'newPassword']);
```

## Standard CRUD APIs

All of these provide the inherited `list`, `get`, `create`, `update`, `delete` methods:

| Class | Base Path |
|-------|-----------|
| `UserApi` | `identity/users` |
| `RoleApi` | `identity/roles` |
| `PermissionApi` | `identity/permissions` |
| `TenantApi` | `identity/tenants` |
| `CompanyApi` | `identity/companies` |
| `BranchApi` | `identity/branches` |
| `ProfileApi` | `identity/profile` |
| `SessionApi` | `identity/sessions` |
| `ApiKeyApi` | `identity/api-keys` |

```php
// Create a user
$user = $identity->users->create([
    'email' => 'new@example.com',
    'firstName' => 'Jane',
    'lastName' => 'Doe',
    'roleId' => $roleId,
]);

// Create an API key
$apiKey = $identity->apiKeys->create([
    'name' => 'CI/CD Key',
    'scopes' => ['read', 'write'],
]);

// List active sessions
$sessions = $identity->sessions->list();
```

## Error Scenarios

| HTTP Status | Cause |
|-------------|-------|
| `400` | Invalid request data (bad email format, weak password) |
| `401` | Invalid credentials or expired token |
| `403` | Insufficient permissions |
| `404` | User, role, or resource not found |
| `409` | Conflict (duplicate email, role name exists) |
| `429` | Rate limit exceeded (login attempts) |
