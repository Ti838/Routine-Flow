
import os
import re

# Template for the clean HEAD section
HEAD_TEMPLATE = """<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{title}</title>
    <link rel="icon" type="image/png" href="{prefix}assets/img/favicon.png">
    <link rel="stylesheet" href="{prefix}assets/css/zoom-fix.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {{
            darkMode: 'class',
            theme: {{
                extend: {{
                    fontFamily: {{
                        sans: ['Inter', 'sans-serif'],
                    }},
                    colors: {{
                        primary: {{
                            blue: '#4f46e5',
                            dark: '#3730a3',
                        }}
                    }}
                }}
            }}
        }}
    </script>
    <script>
        (function () {{
            const saved = localStorage.getItem('theme');
            if (saved === 'dark' || saved === 'light') {{
                if (saved === 'dark') document.documentElement.classList.add('dark');
            }} else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {{
                document.documentElement.classList.add('dark');
            }}
        }})();
    </script>
</head>"""

def repair_file(filepath):
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()

        # Determine path prefix based on file depth
        # Root files have depth 0 relative to base, subdirs have depth 1
        rel_path = os.path.relpath(filepath, os.getcwd())
        depth = len(rel_path.split(os.sep)) - 1
        prefix = "../" * depth if depth > 0 else ""
        
        # 0. Basic check: Does it need repair?
        # If it has truncated links OR empty scripts, we repair.
        needs_repair = '<link href="https:' in content or '<script></script>' in content
        
        if not needs_repair:
            print(f"Skipping {filepath} (seems clean)")
            return

        print(f"Repairing {filepath}...")

        # 1. Extract existing Title
        title_match = re.search(r'<title>(.*?)</title>', content, re.IGNORECASE | re.DOTALL)
        title = title_match.group(1) if title_match else "Routine Flow"

        # 2. Construct new Head
        new_head = HEAD_TEMPLATE.format(title=title, prefix=prefix)

        # 3. Replace Head
        # Regex to find <head>...</head> even if malformed/multiline
        content = re.sub(r'<head>.*?</head>', new_head, content, flags=re.IGNORECASE | re.DOTALL)

        # 4. Remove all empty script tags globally
        content = re.sub(r'\s*<script></script>', '', content)
        
        # 5. Remove any leftover truncated link lines that might be outside text (unlikely but safe)
        content = re.sub(r'<link href="https:\s*', '', content)

        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
            
    except Exception as e:
        print(f"Error repairing {filepath}: {e}")

def main():
    base_dir = os.getcwd()
    print(f"Scanning {base_dir} for damaged files...")
    
    for root, dirs, files in os.walk(base_dir):
        # Skip hidden dirs
        if '.git' in root or '.gemini' in root:
            continue
            
        for file in files:
            if file.endswith('.html') or file.endswith('.view.php'):
                repair_file(os.path.join(root, file))

if __name__ == "__main__":
    main()
