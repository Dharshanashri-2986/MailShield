"""
===========================================================
MailShield

File : ocr.py

Purpose:
Extract text from an uploaded email screenshot.

Input:
Image path (Command Line Argument)

Output:
Extracted text printed to stdout

===========================================================
"""

import sys
import easyocr


# ==========================================================
# Extract text from image
# ==========================================================

def extract_text(image_path):

    # Initialize OCR Reader
    reader = easyocr.Reader(['en'], gpu=False)

    # Read text from image
    result = reader.readtext(image_path, detail=0)

    # Join all detected text
    text = " ".join(result)

    return text


# ==========================================================
# Main Function
# ==========================================================

def main():

    # Check image path
    if len(sys.argv) < 2:

        print("No image path provided.")

        return

    image_path = sys.argv[1]

    extracted_text = extract_text(image_path)

    print(extracted_text)


# ==========================================================
# Program Entry
# ==========================================================

if __name__ == "__main__":

    main()