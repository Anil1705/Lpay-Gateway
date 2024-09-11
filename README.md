# Manual Testing Guide for Payment Notification Endpoint

## Overview

This guide is intended to help you manually test the payment notification integration for our system. The goal is to verify that the `notify.php` script processes payment notifications correctly and updates the database accordingly, especially when automation is not functioning as expected.

## System Details

- **Notification URL**: `https://payment.goodtime365.shop/payfiles/Lpay/notify.php`
- **Testing Environment**: Please use the staging environment or a dedicated testing instance to avoid impacting production data.

## Manual Testing Steps

### 1. Prepare Your Test Data

To test the `notify.php` endpoint, you need to simulate a POST request with the following parameters:

- `payOrderId`: A unique payment order ID.
- `mchOrderNo`: The merchant order number in the format `GINIX{mobile_number}XX{some_number}`. Example: `GINIX9876543210XX4393531`
- `amount`: The amount in the smallest currency unit (e.g., paise for INR). For example, `5000` for an amount of `50.00 INR`.
- `currency`: The currency code. For this test, use `INR`.
- `status`: The status of the payment. Use `2` to indicate a successful payment.

### 2. Send a POST Request

Use a tool like [Postman](https://www.postman.com/) or `curl` to send a POST request to the notification URL. Below is an example of how to use `curl` for testing:

```sh
curl -X POST https://payment.goodtime365.shop/payfiles/Lpay/notify.php 
-H "Content-Type: application/x-www-form-urlencoded" \
-d "payOrderId=TEST12345&mchOrderNo=GINIX9876543210XX4393531&amount=5000&currency=INR&status=2"
