echo "----------------------------"
echo "🔧 RUNNING FULL SYSTEM TEST "
echo "----------------------------"


php artisan test --testsuite=Feature --stop-on-failure

if [ $? -ne 0 ]; then
    echo "❌ TESTS FAILED. STOPPING. PLEASE CHECK THE ERRORS"
    exit 1
else
    echo "---------------------------------"
    echo "ALL TESTS PASSED SUCCESSFULLY! ✅"
    echo "---------------------------------"
fi