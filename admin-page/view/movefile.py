import shutil
import os

def move_files(file_list, destination_folder):
    # Kiểm tra xem thư mục đích có tồn tại không, nếu không, tạo mới
    if not os.path.exists(destination_folder):
        os.makedirs(destination_folder)
    
    # Di chuyển từng tệp tin trong danh sách vào thư mục đích
    for file_path in file_list:
        # Kiểm tra xem tệp tin có tồn tại không
        if os.path.exists(file_path):
            # Tạo đường dẫn mới cho tệp tin trong thư mục đích
            file_name = os.path.basename(file_path)
            destination_path = os.path.join(destination_folder, file_name)
            # Di chuyển tệp tin
            shutil.move(file_path, destination_path)
            print(f"Moved {file_path} to {destination_path}")
        else:
            print(f"File {file_path} does not exist!")

# Danh sách các tệp tin cần di chuyển
files_to_move = [
    "sweetalerts.php", "tooltip.php", "popover.php", "ribbon.php", "clipboard.php", 
    "drag-drop.php", "rangeslider.php", "rating.php", "toastr.php", "text-editor.php", 
    "counter.php", "scrollbar.php", "spinner.php", "notification.php", "lightbox.php", 
    "stickynote.php", "timeline.php", "form-wizard.php", "chart-apex.php", "chart-js.php", 
    "chart-morris.php", "chart-flot.php", "chart-peity.php", "icon-fontawesome.php", 
    "icon-feather.php", "icon-ionic.php", "icon-material.php", "icon-pe7.php", 
    "icon-simpleline.php", "icon-themify.php", "icon-weather.php", "icon-typicon.php", 
    "icon-flag.php", "form-basic-inputs.php", "form-input-groups.php", "form-horizontal.php", 
    "form-vertical.php", "form-mask.php", "form-validation.php", "form-select2.php", 
    "form-fileupload.php", "tables-basic.php", "data-tables.php", "chat.php", "calendar.php", 
    "email.php", "paymentsettings.php", "currencysettings.php", "grouppermissions.php", 
    "taxrates.php"
]


# Thư mục đích
destination_directory = "./manager/"

# Di chuyển các tệp tin vào thư mục đích
move_files(files_to_move, destination_directory)
