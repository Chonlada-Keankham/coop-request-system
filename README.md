# Coop Request System API

## Overview

ระบบ API สำหรับการยื่นคำขอก่อตั้งสหกรณ์ โดยแบ่งผู้ใช้งานออกเป็น 2 Role:

public (ประชาชน): สมัครสมาชิก, ยื่นคำขอ, ดูคำขอของตนเอง
staff (เจ้าหน้าที่): ดูคำขอทั้งหมด และอนุมัติ / ปฏิเสธคำขอ

## Deployment

ระบบถูก Deploy บน Render

Production URL:
https://coop-request-system.onrender.com

API Base URL:
https://coop-request-system.onrender.com/api

Database: PostgreSQL (Render)

## Setup & Run (Local)
1. Clone Project
git clone https://github.com/Chonlada-Keankham/coop-request-system.git

cd coop-request-system

2. Install Dependencies
composer install

3. Setup Environment
cp .env.example .env
php artisan key:generate

4. Database (Local)
ใช้ SQLite โดยตั้งค่า
DB_CONNECTION=sqlite

และสร้างไฟล์
database/database.sqlite

5. Run Migration + Seed
php artisan migrate
php artisan db:seed

หรือ reset ใหม่ทั้งหมด
php artisan migrate:fresh --seed

6. Run Server
php artisan serve

URL สำหรับใช้งาน
http://127.0.0.1:8000

## Seed Users (สำหรับทดสอบ)
1. Public
Email: public@test.com
Password: 123456

2. Staff
Email: staff@test.com
Password: 123456

## Authentication
1. Register
POST /api/register

2. Login
POST /api/login

## Public APIs
1. Create Request
POST /api/requests

Body
{
"coop_name": "Coop Test",
"member_count": 15
}

2. My Requests
GET /api/requests/my

## Staff APIs
1. View All Requests
GET /api/staff/requests

2. Filter Requests
GET /api/staff/requests
Query Params : 
status=pending หรือ approved หรือ rejected


3. Review Request
PATCH /api/staff/requests/review
Query Params
request_id=1
status=approved หรือ rejected

Body
{
"note": "Approved successfully"
}

## Response Format
Success
{
"success": true,
"message": "Success message",
"data": {}
}

Error
{
"success": false,
"message": "Error message",
"errors": {}
}

## HTTP Status Codes
200 OK
201 Created
400 Bad Request
403 Forbidden
404 Not Found
422 Validation Error
500 Server Error

## Postman Collection
ไฟล์:
postman/coop-request-system.postman_collection.json

วิธีใช้งาน

1. เปิด Postman
2. กด Import
3. เลือกไฟล์ postman/coop-request-system.postman_collection.json

ตั้งค่า Variables

Local
base_url = http://127.0.0.1:8000/api

Production
base_url = https://coop-request-system.onrender.com/api

ขั้นตอนทดสอบ

1. Login Public
2. Login Staff
3. Create Request
4. My Requests
5. Staff Requests (All / Filter)
6. Review Request

ระบบจะตั้งค่า token ให้อัตโนมัติหลังจาก Login

## Summary
- ใช้ Laravel + Sanctum
- Authentication แบบ Token
- แบ่ง Role public และ staff
- Public → ยื่นคำขอ + ดูของตัวเอง
- Staff → ดูทั้งหมด + review
- คำขอที่ review แล้ว ไม่สามารถแก้ไขซ้ำได้
- รองรับทั้ง Local (SQLite) และ Production (PostgreSQL)
