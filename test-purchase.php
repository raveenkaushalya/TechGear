<?php
// Test purchase functionality
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Purchase</title>
</head>
<body>
    <h1>Test Purchase API</h1>
    <button onclick="testPurchase()">Purchase 1 Test Product</button>
    <div id="result"></div>

    <script>
    async function testPurchase() {
        const formData = new FormData();
        formData.append('action', 'purchase');
        formData.append('id', '22'); // Test Product ID
        formData.append('quantity', '1');
        
        try {
            const response = await fetch('/TechGear/src/admin/api/products.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            document.getElementById('result').innerHTML = '<pre>' + JSON.stringify(result, null, 2) + '</pre>';
            
            // Check remaining quantity
            const checkResponse = await fetch('/TechGear/src/admin/api/products.php?all=1');
            const products = await checkResponse.json();
            const testProduct = products.data.find(p => p.id == 22);
            
            if (testProduct) {
                document.getElementById('result').innerHTML += 
                    '<p>Remaining quantity: ' + testProduct.quantity + '</p>';
            }
        } catch (error) {
            document.getElementById('result').innerHTML = 'Error: ' + error.message;
        }
    }
    </script>
</body>
</html>
