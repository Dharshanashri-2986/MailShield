"""
===========================================================
MailShield

File : analyze.py

Purpose:
Analyze an email and determine whether it is
likely phishing or safe.

Input:
Command line argument (email text)

Output:
JSON printed to stdout

===========================================================
"""

import sys
import json
import re


# ==========================================================
# Suspicious keyword database
# ==========================================================

KEYWORDS = {

    "urgent":10,
    "verify":10,
    "password":12,
    "account":6,
    "bank":15,
    "login":10,
    "click":8,
    "winner":15,
    "lottery":20,
    "gift":10,
    "reward":10,
    "otp":12,
    "payment":10,
    "security":8,
    "confirm":8,
    "limited":8,
    "immediately":10,
    "update":8,
    "suspended":15,
    "claim":10,
    "congratulations":15,
    "free":8

}


# ==========================================================
# Extract URLs
# ==========================================================

def extract_urls(text):

    pattern = r'https?://\S+|www\.\S+'

    return re.findall(pattern, text)


# ==========================================================
# Extract Email Addresses
# ==========================================================

def extract_emails(text):

    pattern = r'[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}'

    return re.findall(pattern, text)


# ==========================================================
# Find suspicious words
# ==========================================================

def find_keywords(text):

    found = []

    score = 0

    lower_text = text.lower()

    for word in KEYWORDS:

        if word in lower_text:

            found.append(word)

            score += KEYWORDS[word]

    return found, score


# ==========================================================
# URL Risk
# ==========================================================

def url_score(urls):

    score = 0

    reasons = []

    if len(urls) > 0:

        score += 10

        reasons.append("Contains external URL")

    for url in urls:

        if "@" in url:

            score += 20

            reasons.append("Suspicious URL structure")

        if len(url) > 60:

            score += 10

            reasons.append("Very long URL")

        if re.search(r"\d+\.\d+\.\d+\.\d+", url):

            score += 20

            reasons.append("URL contains IP address")

    return score, reasons
# ==========================================================
# Overall Risk Calculation
# ==========================================================

def calculate_risk(text):

    urls = extract_urls(text)

    emails = extract_emails(text)

    keywords, keyword_score = find_keywords(text)

    total_score = keyword_score

    reasons = []

    # URL Analysis
    url_risk, url_reasons = url_score(urls)

    total_score += url_risk

    reasons.extend(url_reasons)

    # Too many links
    if len(urls) >= 3:

        total_score += 10

        reasons.append("Contains multiple links")

    # Too many email addresses
    if len(emails) >= 2:

        total_score += 5

        reasons.append("Contains multiple email addresses")

    # Limit maximum score to 100
    if total_score > 100:

        total_score = 100

    # Risk Level
    if total_score <= 30:

        level = "Safe"

    elif total_score <= 60:

        level = "Medium Risk"

    else:

        level = "High Risk"

    # Add keyword reasons
    for word in keywords:

        reasons.append(f"Detected keyword: {word}")

    # Remove duplicate reasons
    reasons = list(dict.fromkeys(reasons))

    # Analysis summary
    if level == "Safe":

        summary = "The email does not contain many common phishing indicators."

    elif level == "Medium Risk":

        summary = "The email contains several suspicious characteristics. Please verify the sender before taking any action."

    else:

        summary = "The email contains multiple phishing indicators and should be treated as highly suspicious."

    result = {

        "risk_score": total_score,
        "risk_level": level,
        "detected_urls": urls,
        "detected_emails": emails,
        "suspicious_words": keywords,
        "analysis_summary": summary,
        "reasons": reasons

    }

    return result


# ==========================================================
# Main Function
# ==========================================================

def main():

    # Check whether file path is provided
    if len(sys.argv) < 2:

        print(json.dumps({
            "error": "No input file provided."
        }))

        return

    input_file = sys.argv[1]

    try:

        with open(input_file, "r", encoding="utf-8") as file:

            email_text = file.read()

    except Exception as e:

        print(json.dumps({
            "error": str(e)
        }))

        return

    report = calculate_risk(email_text)

    print(json.dumps(report))


# ==========================================================
# Program Entry
# ==========================================================

if __name__ == "__main__":

    main()