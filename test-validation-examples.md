# Quote Form Validation - Test Examples

This document provides examples of valid and invalid quote submission payloads for testing purposes.

## Valid Examples

### Example 1: Quote Without Additional Pages
```json
{
  "contactDetails": {
    "name": "John Smith",
    "email": "john@example.com",
    "phone": "+44 7123 456789",
    "message": "Looking forward to working with you"
  },
  "configuration": {
    "pageType": "single",
    "colors": {
      "primary": "#3B82F6",
      "secondary": "#1E40AF"
    },
    "sections": ["hero", "about", "services", "contact"],
    "additionalPages": [],
    "features": []
  },
  "total": 300,
  "recaptcha_token": "valid-token-here"
}
```
**Status:** ✓ VALID - No additional pages is acceptable

---

### Example 2: Quote With Single Additional Page
```json
{
  "contactDetails": {
    "name": "Jane Doe",
    "email": "jane@example.com",
    "phone": "+44 7987 654321"
  },
  "configuration": {
    "pageType": "multi",
    "colors": {
      "primary": "#10B981",
      "secondary": "#059669"
    },
    "sections": ["hero", "about", "contact"],
    "additionalPages": [
      {
        "id": "services",
        "name": "Services",
        "sections": ["hero", "services-list", "pricing", "cta"]
      }
    ],
    "features": []
  },
  "total": 350,
  "recaptcha_token": "valid-token-here"
}
```
**Status:** ✓ VALID - Single page with all required fields

---

### Example 3: Quote With Multiple Additional Pages
```json
{
  "contactDetails": {
    "name": "Bob Johnson",
    "email": "bob@company.com",
    "phone": "+44 7555 123456",
    "message": "Need this by end of month"
  },
  "configuration": {
    "pageType": "multi",
    "colors": {
      "primary": "#8B5CF6",
      "secondary": "#6D28D9"
    },
    "sections": ["hero", "about", "contact"],
    "additionalPages": [
      {
        "id": "services",
        "name": "Services",
        "sections": ["hero", "services-list", "pricing"]
      },
      {
        "id": "portfolio",
        "name": "Portfolio",
        "sections": ["gallery", "case-studies", "testimonials"]
      },
      {
        "id": "team",
        "name": "Our Team",
        "sections": ["team-grid", "careers"]
      }
    ],
    "features": ["blog", "gallery"]
  },
  "total": 550,
  "recaptcha_token": "valid-token-here"
}
```
**Status:** ✓ VALID - Multiple pages with proper structure

---

## Invalid Examples

### Example 4: Missing Sections in Additional Page
```json
{
  "contactDetails": {
    "name": "Alice Brown",
    "email": "alice@example.com"
  },
  "configuration": {
    "pageType": "multi",
    "colors": {
      "primary": "#EF4444",
      "secondary": "#DC2626"
    },
    "sections": ["hero", "contact"],
    "additionalPages": [
      {
        "id": "services",
        "name": "Services"
        // MISSING: "sections" field
      }
    ],
    "features": []
  },
  "total": 350,
  "recaptcha_token": "valid-token-here"
}
```
**Status:** ✗ INVALID
**Error:** `configuration.additionalPages.0.sections` is required
**Message:** "Each additional page must have at least one section."

---

### Example 5: Empty Sections Array
```json
{
  "contactDetails": {
    "name": "Charlie Wilson",
    "email": "charlie@example.com"
  },
  "configuration": {
    "pageType": "multi",
    "colors": {
      "primary": "#F59E0B",
      "secondary": "#D97706"
    },
    "sections": ["hero", "about"],
    "additionalPages": [
      {
        "id": "blog",
        "name": "Blog",
        "sections": []  // INVALID: Empty array
      }
    ],
    "features": []
  },
  "total": 350,
  "recaptcha_token": "valid-token-here"
}
```
**Status:** ✗ INVALID
**Error:** `configuration.additionalPages.0.sections` must have at least 1 item
**Message:** "Each additional page must have at least one section."

---

### Example 6: Missing Page Name
```json
{
  "contactDetails": {
    "name": "David Lee",
    "email": "david@example.com"
  },
  "configuration": {
    "pageType": "multi",
    "colors": {
      "primary": "#06B6D4",
      "secondary": "#0891B2"
    },
    "sections": ["hero", "contact"],
    "additionalPages": [
      {
        "id": "about-us",
        // MISSING: "name" field
        "sections": ["team", "history"]
      }
    ],
    "features": []
  },
  "total": 350,
  "recaptcha_token": "valid-token-here"
}
```
**Status:** ✗ INVALID
**Error:** `configuration.additionalPages.0.name` is required

---

### Example 7: Missing Page ID
```json
{
  "contactDetails": {
    "name": "Emma Davis",
    "email": "emma@example.com"
  },
  "configuration": {
    "pageType": "multi",
    "colors": {
      "primary": "#EC4899",
      "secondary": "#DB2777"
    },
    "sections": ["hero", "services"],
    "additionalPages": [
      {
        // MISSING: "id" field
        "name": "Portfolio",
        "sections": ["gallery", "projects"]
      }
    ],
    "features": []
  },
  "total": 350,
  "recaptcha_token": "valid-token-here"
}
```
**Status:** ✗ INVALID
**Error:** `configuration.additionalPages.0.id` is required

---

### Example 8: Section Name Too Long
```json
{
  "contactDetails": {
    "name": "Frank Miller",
    "email": "frank@example.com"
  },
  "configuration": {
    "pageType": "multi",
    "colors": {
      "primary": "#14B8A6",
      "secondary": "#0D9488"
    },
    "sections": ["hero", "contact"],
    "additionalPages": [
      {
        "id": "services",
        "name": "Services",
        "sections": [
          "hero",
          "this-is-a-very-long-section-name-that-exceeds-the-maximum-allowed-length-of-fifty-characters"
        ]
      }
    ],
    "features": []
  },
  "total": 350,
  "recaptcha_token": "valid-token-here"
}
```
**Status:** ✗ INVALID
**Error:** `configuration.additionalPages.0.sections.1` must not exceed 50 characters

---

### Example 9: Invalid Hex Color
```json
{
  "contactDetails": {
    "name": "Grace Taylor",
    "email": "grace@example.com"
  },
  "configuration": {
    "pageType": "single",
    "colors": {
      "primary": "blue",  // INVALID: Not a hex color
      "secondary": "#059669"
    },
    "sections": ["hero", "about"],
    "additionalPages": [],
    "features": []
  },
  "total": 300,
  "recaptcha_token": "valid-token-here"
}
```
**Status:** ✗ INVALID
**Error:** `configuration.colors.primary` must be a valid hex color code
**Message:** "Primary color must be a valid hex color code."

---

### Example 10: Multiple Pages with One Invalid
```json
{
  "contactDetails": {
    "name": "Henry Anderson",
    "email": "henry@example.com"
  },
  "configuration": {
    "pageType": "multi",
    "colors": {
      "primary": "#6366F1",
      "secondary": "#4F46E5"
    },
    "sections": ["hero", "about"],
    "additionalPages": [
      {
        "id": "services",
        "name": "Services",
        "sections": ["hero", "services-list", "pricing"]
      },
      {
        "id": "portfolio",
        "name": "Portfolio",
        "sections": []  // INVALID: Second page has empty sections
      }
    ],
    "features": []
  },
  "total": 400,
  "recaptcha_token": "valid-token-here"
}
```
**Status:** ✗ INVALID
**Error:** `configuration.additionalPages.1.sections` must have at least 1 item
**Message:** "Each additional page must have at least one section."

---

## Testing with cURL

### Test Valid Quote Without Additional Pages
```bash
curl -X POST http://localhost/api/quote \
  -H "Content-Type: application/json" \
  -d '{
    "contactDetails": {
      "name": "Test User",
      "email": "test@example.com"
    },
    "configuration": {
      "pageType": "single",
      "colors": {
        "primary": "#3B82F6",
        "secondary": "#1E40AF"
      },
      "sections": ["hero", "about", "contact"],
      "additionalPages": [],
      "features": []
    },
    "total": 300,
    "recaptcha_token": "test-token"
  }'
```

### Test Valid Quote With Additional Pages
```bash
curl -X POST http://localhost/api/quote \
  -H "Content-Type: application/json" \
  -d '{
    "contactDetails": {
      "name": "Test User",
      "email": "test@example.com"
    },
    "configuration": {
      "pageType": "multi",
      "colors": {
        "primary": "#3B82F6",
        "secondary": "#1E40AF"
      },
      "sections": ["hero", "about", "contact"],
      "additionalPages": [
        {
          "id": "services",
          "name": "Services",
          "sections": ["hero", "services-list", "cta"]
        }
      ],
      "features": []
    },
    "total": 350,
    "recaptcha_token": "test-token"
  }'
```

### Test Invalid Quote (Empty Sections)
```bash
curl -X POST http://localhost/api/quote \
  -H "Content-Type: application/json" \
  -d '{
    "contactDetails": {
      "name": "Test User",
      "email": "test@example.com"
    },
    "configuration": {
      "pageType": "multi",
      "colors": {
        "primary": "#3B82F6",
        "secondary": "#1E40AF"
      },
      "sections": ["hero", "about", "contact"],
      "additionalPages": [
        {
          "id": "services",
          "name": "Services",
          "sections": []
        }
      ],
      "features": []
    },
    "total": 350,
    "recaptcha_token": "test-token"
  }'
```

Expected Response:
```json
{
  "message": "The configuration.additionalPages.0.sections field must have at least 1 items.",
  "errors": {
    "configuration.additionalPages.0.sections": [
      "Each additional page must have at least one section."
    ]
  }
}
```

---

## Notes

1. **reCAPTCHA Token**: In production, you need a valid Google reCAPTCHA v3 token. For testing, you may need to disable reCAPTCHA validation or use a test token.

2. **Logo Upload**: When testing with file uploads, use `multipart/form-data` instead of JSON:
```bash
curl -X POST http://localhost/api/quote \
  -F "contactDetails[name]=Test User" \
  -F "contactDetails[email]=test@example.com" \
  -F "configuration[pageType]=single" \
  -F "configuration[colors][primary]=#3B82F6" \
  -F "configuration[colors][secondary]=#1E40AF" \
  -F "configuration[sections][0]=hero" \
  -F "configuration[sections][1]=about" \
  -F "total=300" \
  -F "recaptcha_token=test-token" \
  -F "logo=@/path/to/logo.png"
```

3. **Price Validation**: Total must be between £300 and £10,000

4. **Section Names**: Each section name must be 50 characters or less

5. **Page IDs/Names**: IDs max 50 chars, Names max 100 chars
