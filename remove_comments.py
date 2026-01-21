import re
from pathlib import Path

root_dir = r"c:\Users\TIMON\Desktop\Routine Flow"
total_removed = {"html": 0, "js_line": 0, "js_block": 0}

html_files = list(Path(root_dir).rglob("*.html"))
js_files = list(Path(root_dir).rglob("*.js"))

for html_file in html_files:
    try:
        with open(html_file, 'r', encoding='utf-8', errors='ignore') as f:
            content = f.read()
        
        html_count = len(re.findall(r'<!--.*?-->', content, re.DOTALL))
        total_removed["html"] += html_count
        
        content = re.sub(r'<!--.*?-->', '', content, flags=re.DOTALL)
        
        def replace_script(match):
            script_content = match.group(1)
            line_count = len(re.findall(r'//.*$', script_content, re.MULTILINE))
            block_count = len(re.findall(r'/\*.*?\*/', script_content, re.DOTALL))
            total_removed["js_line"] += line_count
            total_removed["js_block"] += block_count
            
            script_content = re.sub(r'//.*$', '', script_content, flags=re.MULTILINE)
            script_content = re.sub(r'/\*.*?\*/', '', script_content, flags=re.DOTALL)
            return match.group(0).replace(match.group(1), script_content)
        
        content = re.sub(r'<script[^>]*>(.*?)</script>', replace_script, content, flags=re.DOTALL)
        
        with open(html_file, 'w', encoding='utf-8') as f:
            f.write(content)
    except Exception as e:
        print(f"Error processing {html_file}: {e}")

for js_file in js_files:
    try:
        with open(js_file, 'r', encoding='utf-8', errors='ignore') as f:
            content = f.read()
        
        line_count = len(re.findall(r'//.*$', content, re.MULTILINE))
        block_count = len(re.findall(r'/\*.*?\*/', content, re.DOTALL))
        
        total_removed["js_line"] += line_count
        total_removed["js_block"] += block_count
        
        content = re.sub(r'//.*$', '', content, flags=re.MULTILINE)
        content = re.sub(r'/\*.*?\*/', '', content, flags=re.DOTALL)
        
        with open(js_file, 'w', encoding='utf-8') as f:
            f.write(content)
    except Exception as e:
        print(f"Error processing {js_file}: {e}")

print(f"HTML comments removed: {total_removed['html']}")
print(f"JS line comments removed: {total_removed['js_line']}")
print(f"JS block comments removed: {total_removed['js_block']}")
print(f"Total comments removed: {sum(total_removed.values())}")
print(f"\nFiles processed:")
print(f"  HTML files: {len(html_files)}")
print(f"  JS files: {len(js_files)}")
