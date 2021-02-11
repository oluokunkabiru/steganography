ht<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steganograph - Home</title>
    <?php include('includes/head.php') ?>
</head>
<body>
<?php include('includes/header.php') ?>

<div class="jumbotron jumbotron-fluid">
  <div class="container">
    <h1 class="font-weight-bold text-center">
Image Steganography: Hiding text in images using PHP</h1>
    <p>
    Steganography is the art and science of writing hidden message in such a way that no one, apart from the sender and intended recipient, suspects the existence of the message.

Image Steganography is the technique of hiding the data within the image in such a way that prevents the unintended user from the detection of the hidden messages or data.
    </p>
    <h3>Related Theory:</h3>
    <pre>

May 8, 2017
Image Steganography: Hiding text in images using PHP

Steganography is the art and science of writing hidden message in such a way that no one, apart from the sender and intended recipient, suspects the existence of the message.

Image Steganography is the technique of hiding the data within the image in such a way that prevents the unintended user from the detection of the hidden messages or data.
Related Theory:

Images are made up small units of dots called as pixels. Each pixel is represented as 3 bytes : one for Red, one for Green and one for Blue. The composition of these three colors determines the actual color that pixel shows.

Red :

Binary: 11001001

Decimal: 201

Green:

Binary: 11111000

Decimal: 201

Blue:

Binary: 00000011

Decimal: 3

This composition gives rise to orange color.

The basic idea in Image Steganography lies in the fact that a change in the Least Significant Bit (LSB) is not detected by human eye. So we modify the LSB of RGB value to store the hidden message in the message without affecting the color of the image.

In this example, we change the LSB of Blue component only. But we can change the LSB of all Red, Green and Blue component if we want. As we change the LSB of blue component only, the amount of information we can hide is less. We can also store the length of the hidden message in the image which has not been done in this tutorial.

Suppose we want to hide 1101 in the image.

First we get RGB value of each pixel in the image. Since, we are hiding 4 bit data and we are changing blue component, so we would need at 4 pixels of the image.

Suppose we got the following RGB values in the first 4 pixels of the image:

11001100 10010001 00101011

00011000 11110000 11111110

11100010 00100101 01010101

11111101 00001010 01000011

Now, we will replace last bit of each pixel’s RGB value with 1101 consecutively.

So, the new RGB value becomes:

11001100 10010001 00101011

00011000 11110000 11111111

11100010 00100101 01010100

11111101 00001010 01000011

The highlighted bit represent the message we are hiding in the image.

Now we set the new RGB value to the pixel. This change is not detected by human eye and the image looks the same.

The encrypting potion is now complete and we will now decode the hidden message in the pic.

For this we fetch the RGB value of each pixel and then concat the LSB to get our hidden message.
    </pre>
  </div>
</div>

<?php include('includes/footer.php') ?>

</body>
</html>