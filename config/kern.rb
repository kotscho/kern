namespace :kern do
  
  namespace :sql do
    desc "Dump application database"
    task :dump do
      run "mysqldump -u #{db_user} -p #{db_passwd} -h localhost #{db_name} > #{db_backup_path}/#{db_backup_file}"
    end
    
    desc "Import application database"
    task :import do
      run "mysql -u #{db_user} -p #{db_passwd} -h localhost #{dbname} > deploy.sql"
    end
  end
  
end

before(:deploy, "kern:sql;dump")
after(:deploy, "kern:sql:import")
