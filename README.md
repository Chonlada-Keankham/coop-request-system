# Coop Request System API

## Overview
ระบบ API สำหรับการยื่นคำขอก่อตั้งสหกรณ์ แบ่งผู้ใช้งานเป็น 2 Role:

- public (ประชาชน) : สมัครสมาชิก, ยื่นคำขอ, ดูคำขอของตนเอง  
- staff (เจ้าหน้าที่) : ดูคำขอทั้งหมด และอนุมัติ / ปฏิเสธคำขอ  


## Setup & Run System

### 1. Clone Project
git clone https://github.com/Chonlada-Keankham/coop-request-system.git

cd coop-request-system  

### 2. Install Dependencies
composer install  

### 3. Setup Environment
cp .env.example .env  
php artisan key:generate  

ตั้งค่า Database ในไฟล์ `.env`:

DB_CONNECTION=sqlite  

ถ้ายังไม่มีไฟล์ฐานข้อมูล ให้สร้างไฟล์นี้
database/database.sqlite  

## Database
โปรเจกต์นี้ใช้ SQLite สำหรับการรันบนเครื่อง local

Run Migration  
php artisan migrate  

Seed Data  
php artisan db:seed  

หรือ reset ฐานข้อมูลใหม่ทั้งหมด:
php artisan migrate:fresh --seed  

##  Run Server
php artisan serve  

URL:http://127.0.0.1:8000  

## Seed Users (สำหรับทดสอบ)

Public  
    Email: public@test.com  
    Password: 123456  

Staff  
    Email: staff@test.com  
    Password: 123456  


## Authentication

Register  
POST /api/register  

Login  
POST /api/login  

## Public APIs

1. Create Request  
POST /api/requests  

Body:
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

Query Params: 
            status=pending/ approved/ rejected

1. Review Request  
PATCH /api/staff/requests/review  

Query Params:  
            request_id=1  
            status=approved  

Body:
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

### วิธีใช้งาน

1. เปิด Postman  
2. กด Import  
3. เลือกไฟล์:
   postman/coop-request-system.postman_collection.json  

4. ตั้งค่า Collection Variables:
   base_url = http://127.0.0.1:8000/api  

5. เริ่มทดสอบ API ตามลำดับ:
   - Login (Public / Staff)
   - Create Request
   - My Requests
   - Staff Requests (All / Filter)
   - Review Request

ระบบจะตั้งค่า token ให้อัตโนมัติหลังจาก Login

## Summary

- ใช้ Laravel + Sanctum  
- Authentication ด้วย Token  
- แบ่ง Role public / staff  
- Public → ยื่นคำขอ + ดูของตัวเอง  
- Staff → ดูทั้งหมด + review  
- คำขอที่ review แล้ว → ไม่สามารถแก้ไขซ้ำได้  
