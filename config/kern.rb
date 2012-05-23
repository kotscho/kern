namespace :kern do
  namespace :prepare do
    desc "Prepare shared files"
    task :setup do
      run "mkdir -p #{shared_path}/material"
      run "#{sudo} chown deployer:deployer #{shared_path}/material"
    end
    
    desc "Create symlink"
    task :symlink do
      run "ln -s #{shared_path}/material #{current_release}/material"
    end
    
    desc "Modify ownership"
    task :ownership do
      run "#{sudo} chown deployer:deployer #{deploy_to}/releases"
      run "#{sudo} chown deployer:deployer #{deploy_to}/shared"
    end
  end
  
  namespace :sql do
    desc "Dump application database"
    task :dump do
      run "mysqldump -u#{db_user} #{db_name} > #{db_backup_path}/#{db_backup_file}"
    end
    
    #desc "Import application database"
    #task :import do
      #run "cd #{current_path}; mysql -u#{db_user} -h localhost #{dbname} < deploy.sql"
    #end
  end
  
  namespace :migrater do
    desc "migrate mysql db on server"
	task :migrate do
	  run "touch #{shared_path}/migration.list ;
      ls -1v #{current_path}/*.sql 2>/dev/null > #{shared_path}/migration.available;
      diff #{shared_path}/migration.available #{shared_path}/migration.list | awk \"/^</ {print \\$2}\" | while read f ;
      do echo \"migrating $(basename $f)\"; mysql -u#{db_user} #{db_name} < $f && echo #{Time.now.strftime("%d-%m-%Y%H%M")}$f >> #{shared_path}/migration.list ; done;
      rm -f #{shared_path}/migration.available"
     end  
  end
end
#kdos chown after cap deploy setup
after('deploy:setup' , "kern:prepare:ownership")
after('deploy:setup' , "kern:prepare:setup")
before(:deploy, "kern:sql:dump")
after(:deploy , 'kern:migrater:migrate')
after(:deploy , 'kern:prepare:symlink')
#after(:deploy, "kern:sql:import")
