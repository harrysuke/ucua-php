# UCUA Mobile REST API

Backend service for the native **UCUA (U-SEE U-ACT)** Android app.

## Setup

1. Deploy this folder alongside the main app (e.g. `D:\laragon\www\ucua300\ucua_api\`).
2. Point `ucua_api/config.php` at the live database (`ucua-staging`).
3. The `mobile_tokens` table is auto-created on first request.

## Endpoints

Base URL: `http://<host>/ucua300/ucua_api/`

| Method | Endpoint | Auth | Description |
|---|---|---|---|
| POST | `/login.php` | — | Authenticate, returns Bearer token + user profile |
| GET | `/lookups.php` | Bearer | Locations, offences, companies, departments |
| GET | `/reports.php?type=UC` | Bearer | List UC reports (admins see all) |
| GET | `/reports.php?type=UA` | Bearer | List UA reports (admins see all) |
| GET | `/reports.php?id=N` | Bearer | Single report + photo list |
| POST | `/reports.php` | Bearer | Create a UC/UA report |
| PUT | `/reports.php?id=N` | Bearer | Full update (owner or admin level ≥3) |
| PATCH | `/reports.php?id=N` | Bearer | Partial update (owner or admin level ≥3) |
| DELETE | `/reports.php?id=N` | Bearer | Delete report + photos (owner or admin level ≥3) |
| GET | `/index.php?health=1` | — | Health check |

## Login example

```
POST /ucua_api/login.php
Content-Type: application/json

{ "staffId": "JPB0001", "password": "secret" }
```

Response:

```json
{
  "valid": true,
  "token": "<base64url-payload>.<hmac-signature>",
  "user": {
    "staffId": "JPB0001",
    "name": "John Doe",
    "phoneNo": "0123456789",
    "department": "Operations",
    "designation": "Operator",
    "email": "john@example.com",
    "accessLevel": 1,
    "roleName": "Public user"
  }
}
```

## Create report example

```
POST /ucua_api/reports.php
Authorization: Bearer <token>
Content-Type: application/json

{
  "formType": "Unsafe Condition",
  "locationId": "WH-01",
  "conditionDetails": "Faulty railing on gantry walkway"
}
```

## Security notes

- Passwords are verified against the existing legacy **MD5** hash for compatibility.
- Tokens are HMAC-signed and persisted in `mobile_tokens` (7-day expiry).
- Change `JWT_SECRET` in `config.php` before production deployment.
- Always serve over HTTPS in production.
